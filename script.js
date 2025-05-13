// Ambil data dari PHP
const labels = window.chartData?.labels || [];
const dataValues = window.chartData?.values || [];

const barCtx = document.getElementById('barChart').getContext('2d');
const lineCtx = document.getElementById('lineChart').getContext('2d');
const pieCtx = document.getElementById('pieChart').getContext('2d');

// Warna palet
const backgroundColors = ['#1E315C', '#2E4D68', '#C1B4A0', '#060606', '#ff6384', '#36a2eb', '#ffce56'];

const commonData = () => ({
  labels: labels,
  datasets: [{
    label: 'Jumlah Produksi',
    data: dataValues,
    backgroundColor: backgroundColors,
    borderColor: '#060606',
    borderWidth: 1,
    fill: false
  }]
});

const commonOptions = {
  responsive: true,
  plugins: {
    legend: { position: 'top' },
    title: { display: false }
  }
};

new Chart(barCtx, {
  type: 'bar',
  data: commonData(),
  options: commonOptions
});

new Chart(lineCtx, {
  type: 'line',
  data: commonData(),
  options: {
    ...commonOptions,
    elements: {
      line: { tension: 0.3 }
    }
  }
});

new Chart(pieCtx, {
  type: 'pie',
  data: commonData(),
  options: commonOptions
});

// Fungsi untuk form input manual (jika ada)
const dataForm = document.getElementById('dataForm');
if (dataForm) {
  dataForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const label = document.getElementById('labelInput').value;
    const value = parseFloat(document.getElementById('valueInput').value);

    if (label && !isNaN(value)) {
      labels.push(label);
      dataValues.push(value);

      barChart.update();
      lineChart.update();
      pieChart.update();

      this.reset();
    }
  });
}
