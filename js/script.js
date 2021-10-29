const dropElement = document.querySelector('.form__input-container--file');

if (dropElement) {
  const addButton = dropElement.querySelector('.form__input-file-button');
  const photoContainer = dropElement.querySelector('input');
  const previewImage = dropElement.querySelector('.preview');

  const cancelClick = (evt) => {
    evt.preventDefault();
  };

  photoContainer.addEventListener('click', cancelClick);
  photoContainer.addEventListener('change',() => {
    const [file] = photoContainer.files;
    if (file) {
      previewImage.src = URL.createObjectURL(file);
    } else {
      previewImage.src = "";
    }
  })

  addButton.addEventListener('click', (evt) => {
    photoContainer.removeEventListener('click', cancelClick);
    photoContainer.click();
    photoContainer.addEventListener('click', cancelClick);
  });
}
