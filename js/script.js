// Interactive registration form: validation, dynamic fields, and live fee calculation.
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('registrationForm');
  if (!form) return;

  const fullName = document.getElementById('fullName');
  const admissionNo = document.getElementById('admissionNo');
  const email = document.getElementById('email');
  const phone = document.getElementById('phone');
  const yearOfStudy = document.getElementById('yearOfStudy');
  const category = document.getElementById('category');
  const days = document.getElementById('days');
  const meal = document.getElementById('meal');
  const workshop = document.getElementById('workshop');
  const workshopPanel = document.getElementById('workshopPanel');
  const formMessage = document.getElementById('formMessage');

  const formatMoney = amount => 'KSh ' + Math.round(amount).toLocaleString('en-KE');

  function showWorkshop() {
    const needsWorkshop = category.value === 'workshop';
    workshopPanel.hidden = !needsWorkshop;
    workshop.required = needsWorkshop;
    if (!needsWorkshop) workshop.value = '';
    calculateFee();
  }

  function calculateFee() {
    const dayCount = Number(days.value) || 1;
    const registrationFee = 1000;
    const attendanceFee = dayCount * 500;
    const workshopFee = category.value === 'workshop' ? 750 : 0;
    const mealFee = meal.value === 'yes' ? dayCount * 300 : 0;
    // Admission number ends in 0: 10% off registration + attendance for 3 days.
    const discount = dayCount === 3 ? Math.round((registrationFee + attendanceFee) * 0.10) : 0;
    const total = registrationFee + attendanceFee + workshopFee + mealFee - discount;

    document.getElementById('dayCount').textContent = dayCount;
    document.getElementById('registrationLine').textContent = formatMoney(registrationFee);
    document.getElementById('attendanceLine').textContent = formatMoney(attendanceFee);
    document.getElementById('workshopLine').textContent = formatMoney(workshopFee);
    document.getElementById('mealLine').textContent = formatMoney(mealFee);
    document.getElementById('discountLine').textContent = '− ' + formatMoney(discount);
    document.getElementById('totalFee').textContent = formatMoney(total);
  }

  function validateForm() {
    const errors = [];
    if (!fullName.value.trim()) errors.push('Enter your full name.');
    if (!admissionNo.value.trim()) errors.push('Enter your admission/registration number.');
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) errors.push('Enter a valid email address.');
    if (!/^\d{10}$/.test(phone.value.trim())) errors.push('Phone number must contain exactly 10 digits.');
    if (!yearOfStudy.value) errors.push('Select your year of study.');
    if (!category.value) errors.push('Select a participation category.');
    if (!['1', '2', '3'].includes(days.value)) errors.push('Choose between 1 and 3 conference days.');
    if (category.value === 'workshop' && !workshop.value) errors.push('Select a workshop.');
    if (errors.length) {
      formMessage.innerHTML = '<strong>Please correct the following:</strong><ul>' + errors.map(error => '<li>' + error + '</li>').join('') + '</ul>';
      formMessage.classList.add('show');
      formMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
      return false;
    }
    formMessage.textContent = '';
    formMessage.classList.remove('show');
    return true;
  }

  category.addEventListener('change', showWorkshop);
  [days, meal, workshop].forEach(control => control.addEventListener('change', calculateFee));
  [fullName, admissionNo, email, phone].forEach(control => control.addEventListener('input', () => {
    formMessage.classList.remove('show');
  }));
  form.addEventListener('submit', event => {
    if (!validateForm()) event.preventDefault();
  });

  showWorkshop();
  calculateFee();
});
