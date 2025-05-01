const labels = [];
const dataValues = [];

const barCtx = document.getElementById('barChart').getContext('2d');
const lineCtx = document.getElementById('lineChart').getContext('2d');
const pieCtx = document.getElementById('pieChart').getContext('2d');

const commonData = () => ({
  labels: labels,
  datasets: [{
    label: 'Nilai Data',
    data: dataValues,
    backgroundColor: ['#1E315C', '#2E4D68', '#C1B4A0', '#060606', '#ff6384', '#36a2eb', '#ffce56'],
    borderColor: '#060606',
    borderWidth: 1
  }]
});

const commonOptions = {
  responsive: true,
  plugins: {
    legend: { position: 'top' },
    title: { display: false }
  }
};

const barChart = new Chart(barCtx, {
  type: 'bar',
  data: commonData(),
  options: commonOptions
});

const lineChart = new Chart(lineCtx, {
  type: 'line',
  data: commonData(),
  options: commonOptions
});

const pieChart = new Chart(pieCtx, {
  type: 'pie',
  data: commonData(),
  options: commonOptions
});

document.getElementById('dataForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const label = document.getElementById('labelInput').value;
  const value = parseFloat(document.getElementById('valueInput').value);

  if (label && !isNaN(value)) {
    labels.push(label);
    dataValues.push(value);

    barChart.update();
    lineChart.update();
    pieChart.update();

    // Reset form
    this.reset();
  }
});
