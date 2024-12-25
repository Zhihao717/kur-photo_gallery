 // Функция голосования c анимацией нажатия кнопки.
function vote(photoId) {
	const button = event.target;
	if (button.disabled) {
		return;
	}
	
	// Получение значения переменных из CSS-файла.
	const buttonVoteBg = getComputedStyle(document.documentElement).getPropertyValue('--button-vote-bg').trim();
	const buttonVoteBgChanged = getComputedStyle(document.documentElement).getPropertyValue('--button-vote-bg-changed').trim();
	
	button.disabled = true;
	button.textContent = 'Голосование...';
	
	const formData = new FormData();
	formData.append('photo_id', photoId);
	
	fetch('vote.php', {
		method: 'POST',
		body: formData
	})
	.then(response => {
		if (!response.ok) {
			throw new Error('Ошибка соединения.');
		}
		return response.json();
	})
	.then(data => {
		if (data.success) {
			button.textContent = '✓ Проголосовано';
			button.style.backgroundColor = buttonVoteBg;
			setTimeout(() => {
				location.reload();
			}, 1000);
		} else {
			button.textContent = data.message;
			button.style.backgroundColor = buttonVoteBgChanged;
			setTimeout(() => {
				button.disabled = false;
				button.textContent = 'Голосовать';
				button.style.backgroundColor = '';
			}, 2000);
		}
	})
	.catch(error => {
		console.error('Ошибка:', error);
		button.textContent = 'Ошибка';
		button.style.backgroundColor = buttonVoteBgChanged;
		setTimeout(() => {
			button.disabled = false;
			button.textContent = 'Голосовать';
			button.style.backgroundColor = '';
		}, 2000);
	});
}
