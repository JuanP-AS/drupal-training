function closeModal() {
  const modal = document.getElementById('my-custom-modal');
  modal.style.display = 'none';
}

function handleKeyUp(event) {
  if (event.keyCode === 27) {
    closeModal();
  }
}
