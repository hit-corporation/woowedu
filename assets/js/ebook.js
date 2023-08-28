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
    const column = document.createElement('div');
    const figure = document.createElement('div');
    const img = new Image();
    const figcaption = document.createElement('div');
    // add to child
    column.appendChild(figure);
    figure.appendChild(img);
    figure.appendChild(figcaption);
    // styles and attr
    column.classList.add('col-4');
    figure.classList.add('card', 'd-flex', 'flex-wrap', 'justify-content-between');
    img.classList.add('img-thumbnail');
    figcaption.classList.add('card-body');

    // data
    img.src = 'assets/images/ebooks/cover/' + e.cover_img;

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
    const ebooks = await getBooks(1, 6);

    grid.innerHTML = null;
    Array.from([...await ebooks.data], props => {
        grid.appendChild(createBookGrid(props));
    });

})();