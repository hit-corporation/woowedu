'use strict';
import PaginationSystem from '../node_modules/pagination-system/dist/pagination-system.esm.min.js';

const grid = document.querySelector('#grid');
const paginationContainer = document.querySelector('.pagination');

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
    const content = document.createElement('p');
    // add to child
    column.appendChild(figure);
    figure.appendChild(img);
    figure.appendChild(figcaption);
    figcaption.appendChild(title);
    figcaption.appendChild(content);
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
    const kategori = document.createTextNode(e.category_name);
    title.appendChild(judul);
    content.appendChild(kategori);
    console.log(column);
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
        console.error(err);
    }
}




/**
 * @description show list of books
 * @date 8/30/2023 - 2:09:10 PM
 *
 * @async
 * @returns {*}
 */
const viewBookList = async (page, limit) => {
    const ebooks = await getBooks(page, limit);

    if(ebooks.data.length)
    {
        grid.innerHTML = null;
        Array.from(ebooks.data, props => {
            grid.appendChild(createBookGrid(props));
        });
    }

    await pagination(ebooks.totalData, limit);
}

// pagination

let currentPage = 1;
let totalPage = (countData, limit) => Math.ceil(countData / limit);


/**
 * nexPage
 * @date 8/30/2023 - 10:37:24 AM
 *
 * @async
 * @returns {*}
 */
const nextPage = async () => {
    currentPage = (currentPage >= totalPage) ? (currentPage = totalPage) : currentPage++;
    await viewBookList(currentPage, 9);
}


/**
 * previous Page
 * @date 8/30/2023 - 10:37:36 AM
 *
 * @async
 * @returns {*}
 */
const prevPage = async () => {
    currentPage = currentPage <= 0 ? (currentPage = 0) : currentPage--;
    await viewBookList(currentPage, 9);
} 

const pagination = async (countData, limit) => {
    // page
    const liNext = document.createElement('li');
    const liPrev = document.createElement('li');

    const aNext = document.createElement('a');
    const aPrev = document.createElement('a');

    liPrev.classList.add('page-item');
    aPrev.classList.add('page-link');

    aPrev.innerHTML = '<';
    liPrev.appendChild(aPrev);
    paginationContainer.appendChild(liPrev);

    let pages = totalPage(countData, limit);
    Array.from({ length: pages  }, (item, idx) => {
        const li = document.createElement('li');
        const a = document.createElement('a');

        li.classList.add('page-item');
        a.classList.add('page-link');

        a.innerHTML = idx + 1;
        li.appendChild(a);
        paginationContainer.appendChild(li);
    });

    liNext.classList.add('page-item');
    aNext.classList.add('page-link');

    aNext.innerHTML = '>';
    liNext.appendChild(aNext);

    paginationContainer.appendChild(liNext);
}

(async () => {
    
    await viewBookList(1, 9);
})();