const dropElement = document.querySelector('.adding-post__input-file-container');

if (dropElement) {
  const addButton = document.querySelector('.adding-post__input-file-button');
  const photoContainer = dropElement.querySelector('input');

  const cancelClick = (evt) => {
    evt.preventDefault();
  };

  photoContainer.addEventListener('click', cancelClick);

  addButton.addEventListener('click', (evt) => {
    photoContainer.removeEventListener('click', cancelClick);
    photoContainer.click();
    photoContainer.addEventListener('click', cancelClick);
  });
}
