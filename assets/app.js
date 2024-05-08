import './styles/app.css';

document.getElementById('commentBtn').addEventListener('click', function() {
    document.getElementById('comments').style.display = '';
    document.getElementById('infos').style.display = 'none';
    document.getElementById('compos').style.display = 'none';
});

document.getElementById('informationBtn').addEventListener('click', function() {
    document.getElementById('comments').style.display = 'none';
    document.getElementById('infos').style.display = '';
    document.getElementById('compos').style.display = 'none';
});

document.getElementById('compositionBtn').addEventListener('click', function() {
    document.getElementById('comments').style.display = 'none';
    document.getElementById('infos').style.display = 'none';
    document.getElementById('compos').style.display = '';
});