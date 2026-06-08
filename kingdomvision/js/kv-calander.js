class KVCalender {
  static activeInstance = null;

  constructor(input) {
    this.input = input;
    this.selectedDate = null;

    // ---- FIX: establish a fixed allowed range ----
    const today = new Date();
    const curY = today.getFullYear();
    const curM = today.getMonth();

    // baseline = NEXT MONTH from *today*
    const minM = (curM + 1) % 12;
    const minY = curM === 11 ? curY + 1 : curY;

    this.minYear = minY;
    this.minMonth = minM;

    this.maxYear = curY + 2;   // inclusive
    this.maxMonth = 11;        // December

    this.createCalendar();
    this.init();
  }

  init() {
    this.input.setAttribute('readonly', true);

    this.input.addEventListener('click', (e) => {
      e.stopPropagation();
      e.preventDefault();
      this.toggleCalendar();
    });

    document.addEventListener('click', (e) => {
      if (!e.target.closest('.kv-calendar-wrapper') && e.target !== this.input) {
        this.removeCalendar();
      }
    });
  }

  createCalendar() {
    this.wrapper = document.createElement('div');
    this.wrapper.classList.add('kv-calendar-wrapper');
    this.wrapper.innerHTML = `
      <div class="kv-calendar">
        <div class="kv-calendar-header">
          <a href="#" class="kv-prev-month" role="button" aria-label="Previous Month">&#8592;</a>
          <span class="kv-current-month"></span>
          <a href="#" class="kv-next-month" role="button" aria-label="Next Month">&#8594;</a>
          <select class="kv-year-select"></select>
        </div>
        <div class="kv-calendar-body">
          <div class="kv-day-names">
            <span>Su</span><span>Mo</span><span>Tu</span><span>We</span>
            <span>Th</span><span>Fr</span><span>Sa</span>
          </div>
          <div class="kv-days"></div>
        </div>
      </div>
    `;

    // keep clicks inside from reaching form, but allow year select
    this.wrapper.addEventListener('mousedown', (e) => {
      if (!e.target.closest('.kv-calendar')) return;
      if (!e.target.classList.contains('kv-year-select')) e.preventDefault();
      e.stopPropagation();
    });

    this.wrapper.addEventListener('click', (e) => {
      if (!e.target.closest('.kv-calendar')) return;
      e.stopPropagation();
    });
  }

  toggleCalendar() {
    if (KVCalender.activeInstance && KVCalender.activeInstance !== this) {
      KVCalender.activeInstance.removeCalendar();
    }

    if (!this.wrapper.parentNode) {
      KVCalender.activeInstance = this;

      this.wrapper.style.top = this.input.offsetTop + this.input.offsetHeight + 'px';
      this.wrapper.style.left = this.input.offsetLeft + 'px';
      this.input.parentNode.appendChild(this.wrapper);

      this.populateSelectors();
      this.syncWithInput();
      this.renderCalendar();
    } else {
      this.removeCalendar();
    }
  }

  removeCalendar() {
    if (this.wrapper.parentNode) this.wrapper.parentNode.removeChild(this.wrapper);
    if (KVCalender.activeInstance === this) KVCalender.activeInstance = null;
  }

  populateSelectors() {
    this.prevBtn = this.wrapper.querySelector('.kv-prev-month');
    this.nextBtn = this.wrapper.querySelector('.kv-next-month');
    this.currentMonthEl = this.wrapper.querySelector('.kv-current-month');
    this.yearSelect = this.wrapper.querySelector('.kv-year-select');

    // build years only once per open
    this.yearSelect.innerHTML = '';
    for (let y = this.minYear; y <= this.maxYear; y++) {
      const opt = document.createElement('option');
      opt.value = y;
      opt.textContent = y;
      this.yearSelect.appendChild(opt);
    }

    // reset view to last known values if exist
    if (!this.viewYear) this.viewYear = this.minYear;
    if (!this.viewMonth && this.viewMonth !== 0) this.viewMonth = this.minMonth;
    this.yearSelect.value = this.viewYear;
    this.updateMonthLabel();

    // ✅ Remove any previous listeners before adding new ones
    this.prevBtn.replaceWith(this.prevBtn.cloneNode(true));
    this.nextBtn.replaceWith(this.nextBtn.cloneNode(true));
    this.yearSelect.replaceWith(this.yearSelect.cloneNode(true));

    // re-query new clones
    this.prevBtn = this.wrapper.querySelector('.kv-prev-month');
    this.nextBtn = this.wrapper.querySelector('.kv-next-month');
    this.yearSelect = this.wrapper.querySelector('.kv-year-select');

    // reapply handlers cleanly
    this.prevBtn.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      this.changeMonth(-1);
    });

    this.nextBtn.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      this.changeMonth(1);
    });

    this.yearSelect.addEventListener('change', (e) => {
      e.stopPropagation();
      this.viewYear = parseInt(this.yearSelect.value, 10);
      if (this.viewYear === this.minYear && this.viewMonth < this.minMonth) {
        this.viewMonth = this.minMonth;
      }
      if (this.viewYear === this.maxYear && this.viewMonth > this.maxMonth) {
        this.viewMonth = this.maxMonth;
      }
      this.updateMonthLabel();
      this.renderCalendar();
    });
  }


  updateMonthLabel() {
    const months = [
      "January", "February", "March", "April", "May", "June",
      "July", "August", "September", "October", "November", "December"
    ];
    this.currentMonthEl.textContent = months[this.viewMonth];
  }

  // clamp (y,m) into [min, max] month range
  clampToRange(y, m) {
    // below min?
    if (y < this.minYear || (y === this.minYear && m < this.minMonth)) {
      return { y: this.minYear, m: this.minMonth };
    }
    // above max?
    if (y > this.maxYear || (y === this.maxYear && m > this.maxMonth)) {
      return { y: this.maxYear, m: this.maxMonth };
    }
    return { y, m };
  }

  changeMonth(offset) {
    let newMonth = this.viewMonth + offset;
    let newYear = this.viewYear;

    if (newMonth < 0) {
      newMonth = 11;
      newYear--;
    } else if (newMonth > 11) {
      newMonth = 0;
      newYear++;
    }

    ({ y: newYear, m: newMonth } = this.clampToRange(newYear, newMonth));

    this.viewMonth = newMonth;
    this.viewYear = newYear;
    this.yearSelect.value = newYear;

    this.updateMonthLabel();
    this.renderCalendar();
  }

  syncWithInput() {
    const val = this.input.value.trim();

    if (val && /^\d{2}\/\d{2}\/\d{4}$/.test(val)) {
      const [day, month, year] = val.split('/').map(Number);
      this.selectedDate = new Date(year, month - 1, day);

      // open on selected month if within range; otherwise clamp to range
      let y = year;
      let m = month - 1;
      ({ y, m } = this.clampToRange(y, m));
      this.viewYear = y;
      this.viewMonth = m;
    } else {
      this.selectedDate = null;
      this.viewYear = this.minYear;
      this.viewMonth = this.minMonth;
    }

    this.yearSelect.value = this.viewYear;
    this.updateMonthLabel();
  }

  renderCalendar() {
    const daysContainer = this.wrapper.querySelector('.kv-days');

    console.log('min', this.minYear, this.minMonth, 'view', this.viewYear, this.viewMonth);
    const month = this.viewMonth;
    const year = this.viewYear;

    // block months strictly before the fixed min
    if (year < this.minYear || (year === this.minYear && month < this.minMonth)) {
      daysContainer.innerHTML =
        '<div style="text-align:center;padding:10px;color:#888;">Not available</div>';
      return;
    }

    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    daysContainer.innerHTML = '';

    for (let i = 0; i < firstDay.getDay(); i++) {
      daysContainer.appendChild(document.createElement('span'));
    }

    for (let d = 1; d <= lastDay.getDate(); d++) {
      const span = document.createElement('span');
      span.textContent = d;
      span.dataset.date = `${year}-${month + 1}-${d}`;
      span.tabIndex = 0;

      span.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        this.input.value = this.formatDate(span.dataset.date);
        this.selectedDate = new Date(year, month, d);
        this.removeCalendar();
      });

      const isSelected =
        this.selectedDate &&
        d === this.selectedDate.getDate() &&
        month === this.selectedDate.getMonth() &&
        year === this.selectedDate.getFullYear();

      if (isSelected) span.classList.add('selected');
      daysContainer.appendChild(span);
    }
  }

  formatDate(dateStr) {
    const [year, month, day] = dateStr.split('-');
    return `${day.padStart(2, '0')}/${month.padStart(2, '0')}/${year}`;
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const elements = document.querySelectorAll('.kv-calender');
  elements.forEach(el => {
    let input;
    if (el.classList.contains('gfield')) {
      input = el.querySelector('input[type="text"]');
    } else if (el.tagName === 'INPUT') {
      input = el;
    }
    if (input) new KVCalender(input);
  });
});

/**
 * ✅ Re-initialise KVCalendar whenever Gravity Forms re-renders (AJAX errors etc.)
 */
function reinitKVCalendar() {
  const elements = document.querySelectorAll('.kv-calender');
  elements.forEach(el => {
    let input;
    if (el.classList.contains('gfield')) {
      input = el.querySelector('input[type="text"]');
    } else if (el.tagName === 'INPUT') {
      input = el;
    }
    if (input && !input.dataset.kvInit) {
      new KVCalender(input);
      input.dataset.kvInit = 'true';
    }
  });
}

// Run again after Gravity Forms AJAX validation or re-render
jQuery(document).on('gform_post_render', function () {
  // Optional small delay ensures compatibility with slow themes
  setTimeout(reinitKVCalendar, 100);
});


