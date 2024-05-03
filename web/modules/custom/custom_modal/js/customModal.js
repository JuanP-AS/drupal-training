window.onload = () => {
  const modal = document.getElementById('my-custom-modal');
  modal.focus();
};

const closeModal = () => {
  const modal = document.getElementById('my-custom-modal');
  modal.style.display = 'none';
}

const handleKeyUp = (event) => {
  if (event.keyCode === 27) {
    closeModal();
  }
}
