const title = document.querySelector('#title');
const text = document.querySelector('#text');
const btn = document.querySelector('#btn');

btn.addEventListener('click', () => {
	const data = {
		title: title.value,
		text: text.value,
		author: '[JS]SNBZK'
	}
	fetch('/SocNet/api.php', {
		method: 'POST',
		headers: {'Content-Type': 'application/json'},
		body: JSON.stringify(data)
	})
	.then(response => response.json())
	.then(data => {
		console.log(data);
	})
	title.value = '';
	text.value = '';
});
