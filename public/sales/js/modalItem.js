document.getElementById('uploadImagesBtn').addEventListener('click', () => {
    const formData = new FormData();

    const image1 = document.getElementById('image1').files[0];
    const image2 = document.getElementById('image2').files[0];

    formData.append('image1', image1);
    formData.append('image2', image2);

    formData.append('image1_width', document.getElementById('image1_width').value);
    formData.append('image1_height', document.getElementById('image1_height').value);
    formData.append('image2_width', document.getElementById('image2_width').value);
    formData.append('image2_height', document.getElementById('image2_height').value);

    fetch('images.estimates.upload', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        console.log("Résultat du calcul :", data);
    })
    .catch(err => console.error("Erreur:", err));
});
