/*!
	Scripts: silverstripe-announcements module
	Author: Andrew Haine
	Website: http://www.andrewhaine.co.uk
	Contact: hello@andrewhaine.co.uk
*/

"use strict";

const pushPage = {
	pagePunch: 0,
	allMessages: document.querySelectorAll('.ss_announcement__takes-space--1'),
	getHeights(messages) {

		// Loop through our messages and store the total of their heights
		for(let i = 0; i < messages.length; i++) {
			let messageHeight = messages[i].clientHeight;
			pushPage.pagePunch += parseInt(messageHeight, 10);

			if(i > 0) {
				messages[i-1].style.top = `${messageHeight}px`;
			}
		}

	},
	updatePadding(messages) {

		// Reset padding
		pushPage.pagePunch = 0;

		// Get the total message heights
		pushPage.getHeights(messages);

		// Update the DOM
		document.body.setAttribute('style', `padding-top: ${pushPage.pagePunch}px`);

	},
	init() {

		// Update the padding on page load
		pushPage.updatePadding(pushPage.allMessages);

		// Update the padding one screen width change
		window.onresize = () => {
			pushPage.updatePadding(pushPage.allMessages);
		}

	}
}

const messageAction = {
	findMessageOuter(element, cls = "ss_announcement__message-outer") {

		// Loop through parent element until we find one with the desired class
		while ((element = element.parentElement) && !element.classList.contains(cls));
	    return element;

	},
	removeMessage(message) {

		// Find the parent of the action button
		let parentMessage = messageAction.findMessageOuter(message);

		// Remove message with animation class
		parentMessage.classList.add('ss_announcement__animating-out');

		setTimeout(() => {

			// Remove message from DOM
			parentMessage.parentNode.removeChild(parentMessage);

			// Update the page padding for messages that push the page down
			pushPage.updatePadding(pushPage.allMessages);
		}, 400);

	}
}

pushPage.init();
