window.onload = () => {
	let elements = document.querySelectorAll('.food-vote');

	for (let i = 0; i < elements.length; i++) {
		let element = elements[i];

		element.onclick = (event) => {
			const http = new XMLHttpRequest;

			http.open('GET', '/?foodId=' + element.getAttribute('data-food') + '&do=vote');
			http.send();

			let votesElement = element.querySelector('.votes');
			votesElement.innerHTML = (parseInt(votesElement.innerHTML) + 1).toString();
		};
	}
};