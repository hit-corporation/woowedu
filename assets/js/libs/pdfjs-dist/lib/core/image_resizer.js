/**
 * @licstart The following is the entire license notice for the
 * JavaScript code in this page
 *
 * Copyright 2023 Mozilla Foundation
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @licend The above is the entire license notice for the
 * JavaScript code in this page
 */
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.ImageResizer = void 0;
var _util = require("../shared/util.js");
const MIN_IMAGE_DIM = 2048;
const MAX_IMAGE_DIM = 65537;
const MAX_ERROR = 128;
class ImageResizer {
  constructor(imgData, isMask) {
    this._imgData = imgData;
    this._isMask = isMask;
  }
  static needsToBeResized(width, height) {
    if (width <= this._goodSquareLength && height <= this._goodSquareLength) {
      return false;
    }
    const {
      MAX_DIM
    } = this;
    if (width > MAX_DIM || height > MAX_DIM) {
      return true;
    }
    const area = width * height;
    if (this._hasMaxArea) {
      return area > this.MAX_AREA;
    }
    if (area < this._goodSquareLength ** 2) {
      return false;
    }
    if (this._areGoodDims(width, height)) {
      this._goodSquareLength = Math.max(this._goodSquareLength, Math.floor(Math.sqrt(width * height)));
      return false;
    }
    this._goodSquareLength = this._guessMax(this._goodSquareLength, MAX_DIM, MAX_ERROR, 0);
    const maxArea = this.MAX_AREA = this._goodSquareLength ** 2;
    return area > maxArea;
  }
  static get MAX_DIM() {
    return (0, _util.shadow)(this, "MAX_DIM", this._guessMax(MIN_IMAGE_DIM, MAX_IMAGE_DIM, 0, 1));
  }
  static get MAX_AREA() {
    this._hasMaxArea = true;
    return (0, _util.shadow)(this, "MAX_AREA", this._guessMax(ImageResizer._goodSquareLength, this.MAX_DIM, MAX_ERROR, 0) ** 2);
  }
  static set MAX_AREA(area) {
    if (area >= 0) {
      this._hasMaxArea = true;
      (0, _util.shadow)(this, "MAX_AREA", area);
    }
  }
  static setMaxArea(area) {
    if (!this._hasMaxArea) {
      this.MAX_AREA = area >> 2;
    }
  }
  static _areGoodDims(width, height) {
    try {
      const canvas = new OffscreenCanvas(width, height);
      const ctx = canvas.getContext("2d");
      ctx.fillRect(0, 0, 1, 1);
      const opacity = ctx.getImageData(0, 0, 1, 1).data[3];
      canvas.width = canvas.height = 1;
      return opacity !== 0;
    } catch (e) {
      return false;
    }
  }
  static _guessMax(start, end, tolerance, defaultHeight) {
    while (start + tolerance + 1 < end) {
      const middle = Math.floor((start + end) / 2);
      const height = defaultHeight || middle;
      if (this._areGoodDims(middle, height)) {
        start = middle;
      } else {
        end = middle;
      }
    }
    return start;
  }
  static async createImage(imgData, isMask = false) {
    return new ImageResizer(imgData, isMask)._createImage();
  }
  async _createImage() {
    const data = this._encodeBMP();
    const blob = new Blob([data.buffer], {
      type: "image/bmp"
    });
    const bitmapPromise = createImageBitmap(blob);
    const {
      MAX_AREA,
      MAX_DIM
    } = ImageResizer;
    const {
      _imgData: imgData
    } = this;
    const {
      width,
      height
    } = imgData;
    const minFactor = Math.max(width / MAX_DIM, height / MAX_DIM, Math.sqrt(width * height / MAX_AREA));
    const firstFactor = Math.max(minFactor, 2);
    const factor = Math.round(10 * (minFactor + 1.25)) / 10 / firstFactor;
    const N = Math.floor(Math.log2(factor));
    const steps = new Array(N + 2).fill(2);
    steps[0] = firstFactor;
    steps.splice(-1, 1, factor / (1 << N));
    let newWidth = width;
    let newHeight = height;
    let bitmap = await bitmapPromise;
    for (const step of steps) {
      const prevWidth = newWidth;
      const prevHeight = newHeight;
      newWidth = Math.floor(newWidth / step) - 1;
      newHeight = Math.floor(newHeight / step) - 1;
      const canvas = new OffscreenCanvas(newWidth, newHeight);
      const ctx = canvas.getContext("2d");
      ctx.drawImage(bitmap, 0, 0, prevWidth, prevHeight, 0, 0, newWidth, newHeight);
      bitmap = canvas.transferToImageBitmap();
    }
    imgData.data = null;
    imgData.bitmap = bitmap;
    imgData.width = newWidth;
    imgData.height = newHeight;
    return imgData;
  }
  _encodeBMP() {
    const {
      width,
      height,
      kind
    } = this._imgData;
    let data = this._imgData.data;
    let bitPerPixel;
    let colorTable = new Uint8Array(0);
    let maskTable = colorTable;
    let compression = 0;
    switch (kind) {
      case _util.ImageKind.GRAYSCALE_1BPP:
        {
          bitPerPixel = 1;
          colorTable = new Uint8Array(this._isMask ? [255, 255, 255, 255, 0, 0, 0, 0] : [0, 0, 0, 0, 255, 255, 255, 255]);
          const rowLen = width + 7 >> 3;
          const rowSize = rowLen + 3 & -4;
          if (rowLen !== rowSize) {
            const newData = new Uint8Array(rowSize * height);
            let k = 0;
            for (let i = 0, ii = height * rowLen; i < ii; i += rowLen, k += rowSize) {
              newData.set(data.subarray(i, i + rowLen), k);
            }
            data = newData;
          }
          break;
        }
      case _util.ImageKind.RGB_24BPP:
        {
          bitPerPixel = 24;
          if (width & 3) {
            const rowLen = 3 * width;
            const rowSize = rowLen + 3 & -4;
            const extraLen = rowSize - rowLen;
            const newData = new Uint8Array(rowSize * height);
            let k = 0;
            for (let i = 0, ii = height * rowLen; i < ii; i += rowLen) {
              const row = data.subarray(i, i + rowLen);
              for (let j = 0; j < rowLen; j += 3) {
                newData[k++] = row[j + 2];
                newData[k++] = row[j + 1];
                newData[k++] = row[j];
              }
              k += extraLen;
            }
            data = newData;
          } else {
            for (let i = 0, ii = data.length; i < ii; i += 3) {
              const tmp = data[i];
              data[i] = data[i + 2];
              data[i + 2] = tmp;
            }
          }
          break;
        }
      case _util.ImageKind.RGBA_32BPP:
        bitPerPixel = 32;
        compression = 3;
        maskTable = new Uint8Array(4 + 4 + 4 + 4 + 52);
        const view = new DataView(maskTable.buffer);
        if (_util.FeatureTest.isLittleEndian) {
          view.setUint32(0, 0x000000ff, true);
          view.setUint32(4, 0x0000ff00, true);
          view.setUint32(8, 0x00ff0000, true);
          view.setUint32(12, 0xff000000, true);
        } else {
          view.setUint32(0, 0xff000000, true);
          view.setUint32(4, 0x00ff0000, true);
          view.setUint32(8, 0x0000ff00, true);
          view.setUint32(12, 0x000000ff, true);
        }
        break;
      default:
        throw new Error("invalid format");
    }
    let i = 0;
    const headerLength = 40 + maskTable.length;
    const fileLength = 14 + headerLength + colorTable.length + data.length;
    const bmpData = new Uint8Array(fileLength);
    const view = new DataView(bmpData.buffer);
    view.setUint16(i, 0x4d42, true);
    i += 2;
    view.setUint32(i, fileLength, true);
    i += 4;
    view.setUint32(i, 0, true);
    i += 4;
    view.setUint32(i, 14 + headerLength + colorTable.length, true);
    i += 4;
    view.setUint32(i, headerLength, true);
    i += 4;
    view.setInt32(i, width, true);
    i += 4;
    view.setInt32(i, -height, true);
    i += 4;
    view.setUint16(i, 1, true);
    i += 2;
    view.setUint16(i, bitPerPixel, true);
    i += 2;
    view.setUint32(i, compression, true);
    i += 4;
    view.setUint32(i, 0, true);
    i += 4;
    view.setInt32(i, 0, true);
    i += 4;
    view.setInt32(i, 0, true);
    i += 4;
    view.setUint32(i, colorTable.length / 4, true);
    i += 4;
    view.setUint32(i, 0, true);
    i += 4;
    bmpData.set(maskTable, i);
    i += maskTable.length;
    bmpData.set(colorTable, i);
    i += colorTable.length;
    bmpData.set(data, i);
    return bmpData;
  }
}
exports.ImageResizer = ImageResizer;
ImageResizer._goodSquareLength = MIN_IMAGE_DIM;