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
			this.pagePunch += parseInt(messageHeight, 10);

			// Shift the previous message down to avoid announcements overlapping at the top of the page
			if(i > 0) {
				messages[i-1].style.top = `${messageHeight}px`;
			}
		}

	},
	updatePadding(messages) {

		// Reset padding
		this.pagePunch = 0;

		// Get the total message heights
		this.getHeights(messages);

		// Update the DOM
		document.body.setAttribute('style', `padding-top: ${pushPage.pagePunch}px`);

	},
	init() {

		/* Add body class containing padding transition, this should not be added
			on page load as the animation is jarring
		*/
		setTimeout(() => {
			document.body.classList.add('ss_announcements__body--animatable');
		}, 400);

		// Update the padding on page load
		this.updatePadding(pushPage.allMessages);

		// Update the padding one screen width change
		window.onresize = () => {
			this.updatePadding(pushPage.allMessages);
		}

	}
}

const announcementCookie = {
	allSiteAnnouncements: document.querySelectorAll('.ss_announcement__message-outer'),
	getCookie() {
		let cookie = document.cookie,
			cookieArray = cookie.split(';');

		// Find our cookie from the page cookie
		for(let j = 0; j<cookieArray.length; j++) {
			if(cookieArray[j].includes('ssAnnouncementCookie')) {
				return cookieArray[j].split('=')[1];
			}
		}
	},
	setCookie(messageID) {
		if(document.cookie) {

			// Split our ID string
			let existingCookieString = this.getCookie(),
				splitExistingCookie = existingCookieString.split(',');

			// Add our closed message ID to the cookie string
			splitExistingCookie.push(String(messageID));

			// Rebuild the cookie
			let newIDListToString = splitExistingCookie.join(',')

			document.cookie = `ssAnnouncementCookie=${newIDListToString}; path=/`;

		} else {

			// If no cookie exists set a new one to the ID of the closed announcement
			document.cookie = `ssAnnouncementCookie=${String(messageID)}; path=/`;
		}
	},
	removeHiddenClass(message) {

		// Use a hidden class to prevent prevously closed announement occasionally flickering
		message.classList.remove('ss_announcement--hidden');
	},
	removeMessageOnInit() {
		if(document.cookie) {

			// Split our ID string
			let existingCookieString = announcementCookie.getCookie(),
				splitExistingCookie = existingCookieString.split(',');

			// Loop through IDs retrieved from the cookie
			for(let l = 0; l<splitExistingCookie.length; l++) {
				let messageToRemove = document.getElementById(splitExistingCookie[l]);

				if(messageToRemove){

					// Remove announcements if their ID exists in the cookie
					messageToRemove.parentNode.removeChild(messageToRemove);
				}
			}
		}

		// Remove the hidden class from allowed messages
		for(let m = 0; m<announcementCookie.allSiteAnnouncements.length; m++) {
			this.allSiteAnnouncements[m].classList.remove('ss_announcement--hidden');
		}
	}
}

const messageAction = {
	findMessageOuter(element, cls = "ss_announcement__message-outer") {

		// Loop through parent element until we find one with the desired class
		while ((element = element.parentElement) && !element.classList.contains(cls));
	    return element;

	},
	closeMessage(message) {

		// Find the parent of the action button
		let parentMessage = messageAction.findMessageOuter(message);

		announcementCookie.setCookie(parentMessage.id);

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

announcementCookie.removeMessageOnInit();
pushPage.init();
