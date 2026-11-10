//Récupère le champ de sélection et l'aperçu de l'image
const pictureInput = document.getElementById("picture");
const picturePreview = document.getElementById("book_picture_preview");

//Affiche un aperçu de la nouvelle image sélectionnée
if (pictureInput && picturePreview) {
  pictureInput.addEventListener("change", function () {
    const file = this.files[0];

    if (file && file.type.startsWith("image/")) {
      picturePreview.src = URL.createObjectURL(file);
    }
  });
}
