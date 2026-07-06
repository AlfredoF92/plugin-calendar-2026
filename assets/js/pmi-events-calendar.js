(function () {
	'use strict';

	if (typeof pmiEventsCalendar === 'undefined') {
		return;
	}

	function request(action, payload) {
		var body = new URLSearchParams();
		body.append('action', action);
		body.append('nonce', pmiEventsCalendar.nonce);

		Object.keys(payload).forEach(function (key) {
			if (payload[key] !== undefined && payload[key] !== null) {
				body.append(key, payload[key]);
			}
		});

		return fetch(pmiEventsCalendar.ajaxUrl, {
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

	function setLoading(calendar, loading) {
		calendar.classList.toggle('is-loading', loading);
	}

	function replaceCalendar(oldNode, html) {
		var wrapper = document.createElement('div');
		wrapper.innerHTML = html.trim();
		var newNode = wrapper.firstElementChild;

		if (!newNode || !oldNode.parentNode) {
			return null;
		}

		oldNode.parentNode.replaceChild(newNode, oldNode);
		bindCalendar(newNode);
		return newNode;
	}

	function updateSelectedDay(calendar, date) {
		calendar.dataset.selected = date;

		calendar.querySelectorAll('.pmi-events-calendar__day[data-date]').forEach(function (button) {
			var isSelected = button.dataset.date === date;
			button.classList.toggle('pmi-events-calendar__day--selected', isSelected);
			button.setAttribute('aria-current', isSelected ? 'date' : 'false');
		});
	}

	function loadDayEvents(calendar, date) {
		var list = calendar.querySelector('[data-events-list]');
		var heading = calendar.querySelector('.pmi-events-calendar__events-date');

		if (!list) {
			return;
		}

		setLoading(calendar, true);

		request('pmi_events_day', { date: date })
			.then(function (response) {
				if (!response.success) {
					throw new Error('day');
				}

				updateSelectedDay(calendar, date);
				list.innerHTML = response.data.html;

				if (heading && response.data.label) {
					var label = heading.querySelector('.pmi-events-calendar__events-date-text');
					if (label) {
						label.textContent = response.data.label;
					} else {
						heading.textContent = response.data.label;
					}
				}
			})
			.catch(function () {
				list.innerHTML = '<p class="pmi-events-calendar__empty">' + pmiEventsCalendar.i18n.error + '</p>';
			})
			.finally(function () {
				setLoading(calendar, false);
			});
	}

	function loadMonth(calendar, year, month, selectedDate) {
		setLoading(calendar, true);

		request('pmi_events_calendar', {
			year: year,
			month: month,
			selected_date: selectedDate,
			title: calendar.dataset.title || '',
			calendar_url: calendar.dataset.calendarUrl || '',
			calendar_link: calendar.dataset.calendarLink || '',
		})
			.then(function (response) {
				if (!response.success || !response.data.html) {
					throw new Error('month');
				}

				replaceCalendar(calendar, response.data.html);
			})
			.catch(function () {
				setLoading(calendar, false);
				window.alert(pmiEventsCalendar.i18n.error);
			});
	}

	function bindCalendar(calendar) {
		calendar.addEventListener('click', function (event) {
			var navButton = event.target.closest('.pmi-events-calendar__nav-btn');
			if (navButton) {
				event.preventDefault();
				loadMonth(
					calendar,
					navButton.dataset.year,
					navButton.dataset.month,
					calendar.dataset.selected
				);
				return;
			}

			var dayButton = event.target.closest('.pmi-events-calendar__day[data-date]');
			if (dayButton) {
				event.preventDefault();
				loadDayEvents(calendar, dayButton.dataset.date);
			}
		});
	}

	document.querySelectorAll('.pmi-events-calendar').forEach(bindCalendar);
})();
