document.getElementById('uploadForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData();
    const fileInput = document.getElementById('fileInput');
    formData.append('file', fileInput.files[0]);

    fetch('upload.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const link = document.createElement('a');
                link.href = data.file_url;
                link.textContent = 'Download PDF';
                document.getElementById('result').innerHTML = '';
                document.getElementById('result').appendChild(link);
            } else {
                document.getElementById('result').textContent = 'Fehler: ' + data.error;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('result').textContent = 'Ein Fehler ist aufgetreten';
        });
});
