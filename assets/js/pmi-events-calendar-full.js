(function () {
	'use strict';

	if (typeof pmiEventsCalendarFull === 'undefined') {
		return;
	}

	function request(action, payload) {
		var body = new URLSearchParams();
		body.append('action', action);
		body.append('nonce', pmiEventsCalendarFull.nonce);

		Object.keys(payload).forEach(function (key) {
			if (payload[key] !== undefined && payload[key] !== null) {
				body.append(key, payload[key]);
			}
		});

		return fetch(pmiEventsCalendarFull.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
			},
			body: body.toString(),
		}).then(function (response) {
			return response.json();
		});
	}

	function openPopup(shell, date, label, html) {
		var popup = shell.querySelector('[data-pmi-events-popup]');
		if (!popup) {
			return;
		}

		var dateEl = popup.querySelector('[data-popup-date]');
		var bodyEl = popup.querySelector('[data-popup-body]');

		if (dateEl) {
			dateEl.textContent = label || date;
		}
		if (bodyEl) {
			bodyEl.innerHTML = html;
		}

		popup.hidden = false;
		document.addEventListener('keydown', onEscape);
	}

	function closePopup(shell) {
		var popup = shell.querySelector('[data-pmi-events-popup]');
		if (popup) {
			popup.hidden = true;
		}
		document.removeEventListener('keydown', onEscape);
	}

	function onEscape(event) {
		if (event.key === 'Escape') {
			document.querySelectorAll('[data-pmi-events-popup]').forEach(function (popup) {
				popup.hidden = true;
			});
		}
	}

	function showDayPopup(shell, date) {
		var calendar = shell.querySelector('.pmi-events-full-calendar');
		if (calendar) {
			calendar.classList.add('is-loading');
		}

		request('pmi_events_day', { date: date })
			.then(function (response) {
				if (!response.success) {
					throw new Error('day');
				}
				openPopup(shell, date, response.data.label, response.data.html);
			})
			.catch(function () {
				openPopup(shell, date, '', '<p class="pmi-events-calendar__empty">' + pmiEventsCalendarFull.i18n.error + '</p>');
			})
			.finally(function () {
				if (calendar) {
					calendar.classList.remove('is-loading');
				}
			});
	}

	function loadMonth(shell, year, month) {
		var calendar = shell.querySelector('.pmi-events-full-calendar');
		if (!calendar) {
			return;
		}

		calendar.classList.add('is-loading');

		request('pmi_events_calendar_full', {
			year: year,
			month: month,
			title: calendar.dataset.title || '',
			event_limit: calendar.dataset.eventLimit || 2,
		})
			.then(function (response) {
				if (!response.success || !response.data.html) {
					throw new Error('month');
				}
				shell.innerHTML = response.data.html;
				bindShell(shell);
			})
			.catch(function () {
				calendar.classList.remove('is-loading');
				window.alert(pmiEventsCalendarFull.i18n.error);
			});
	}

	function bindShell(shell) {
		shell.addEventListener('click', function (event) {
			var navButton = event.target.closest('.pmi-events-full-calendar__nav-btn, .pmi-events-full-calendar__today-btn');
			if (navButton && !navButton.disabled) {
				event.preventDefault();
				loadMonth(shell, navButton.dataset.year, navButton.dataset.month);
				return;
			}

			var moreButton = event.target.closest('.pmi-events-full-calendar__more');
			if (moreButton) {
				event.preventDefault();
				showDayPopup(shell, moreButton.dataset.date);
				return;
			}

			var eventItem = event.target.closest('.pmi-events-full-calendar__event');
			if (eventItem) {
				event.preventDefault();
				showDayPopup(shell, eventItem.dataset.date);
				return;
			}

			var cell = event.target.closest('.pmi-events-full-calendar__cell--has-events');
			if (cell) {
				showDayPopup(shell, cell.dataset.date);
				return;
			}

			var closeTrigger = event.target.closest('[data-popup-close]');
			if (closeTrigger) {
				closePopup(shell);
			}
		});
	}

	document.querySelectorAll('.pmi-events-full-calendar-shell').forEach(bindShell);
})();
