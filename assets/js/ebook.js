'use strict';

var currentPage = 1;


/**
 * @descsription add new grid for a book for list
 * @date 8/28/2023 - 2:50:46 PM
 *
 * @param {*} e
 * @returns {HTMLDivElement}
 */
const createBookGrid = e => {
    const imgWidth = 78 * 1.5;
    const imgHeight = 105 * 1.5;
    const column = document.createElement('div');
    const figure = document.createElement('a');
    const img = new Image(imgWidth, imgHeight);
    const figcaption = document.createElement('div');
    const title = document.createElement('p');
    // add to child
    column.appendChild(figure);
    figure.appendChild(img);
    figure.appendChild(figcaption);
    figcaption.appendChild(title);
    // styles and attr
    column.classList.add('col-4', 'p-2');
    figure.classList.add('card', 'flex-row', 'flex-nowrap', 'justify-content-around', 'border-0', 'shadow-sm');
    figure.style.cursor = 'pointer';
    figcaption.classList.add('card-body', 'flex-grow-1');
    img.classList.add('my-2', 'ms-2');
    title.classList.add('title', 'p-0');
    // figure
    figure.onmouseover = e => { 
        figure.classList.remove('shadow-sm');
        figure.classList.add('shadow'); 
    };
    figure.onmouseout = e => { 
        figure.classList.remove('shadow'); 
        figure.classList.add('shadow-sm'); 
    };
    // image
    const imgFileName = ('assets/images/ebooks/cover/' + e.cover_img).split('.');
    img.src = imgFileName[0] + '_thumb.jpg';
    img.onerror = () => {
        img.src = 'assets/images/ebooks/cover/default.png';
    };
    // title
    const judul = document.createTextNode(e.title);
    title.appendChild(judul);

    return column;
}


/**
 * @description Get All Books
 * @date 8/28/2023 - 2:58:56 PM
 *
 * @async
 * @returns {Object}
 */
const getBooks = async (page, count) => {
    try
    {

        const url = new URL("ebook/list", BASE_URL);
        url.searchParams.append('count', count);
        url.searchParams.append('page', page);
        const f = await fetch(url.href);

        return await f.json();
    }
    catch(err)
    {
        
    }
}


(async () => {
    const grid = document.querySelector('#grid');
    const ebooks = await getBooks(1, 9);

    grid.innerHTML = null;
    Array.from(ebooks.data, props => {
        grid.appendChild(createBookGrid(props));
    });

})();