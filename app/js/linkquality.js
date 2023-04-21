// Link quality gauge for ChartJS

// Support for dark theme
const theme = getCookie('theme');
let bgColor1, bgColor2, borderColor, labelColor;

if (theme === 'lightsout.css') {
  bgColor1 = '#141414';
  bgColor2 = '#141414';
  borderColor = 'rgba(37, 153, 63, 1)';
  labelColor = 'rgba(37, 153, 63, 1)';
} else {
  bgColor1 = '#d4edda';
  bgColor2 = '#eaecf4';
  borderColor = 'rgba(147, 210, 162, 1)';
  labelColor = 'rgba(130, 130, 130, 1)';
}

const data1 = {
  datasets: [{
    data: [linkQ, 100 - linkQ],
    backgroundColor: [bgColor1, bgColor2],
    borderColor: borderColor,
  }],
};

const config = {
  type: 'doughnut',
  data: data1,
  options: {
    aspectRatio: 2,
    responsive: true,
    maintainAspectRatio: false,
    tooltips: {
      enabled: false
    },
    hover: {
      mode: null
    },
    legend: {
      display: false,
    },
    rotation: (2 / 3) * Math.PI,
    circumference: (1 + (2 / 3)) * Math.PI,
    cutoutPercentage: 80,
    animation: {
      animateScale: false,
      animateRotate: true
    }
  },
  centerText: {
    display: true,
    text: `${linkQ}%`
  },
  plugins: [{
    beforeDraw: function(chart) {
      if (chart.config.centerText.display) {
        drawLinkQ(chart);
      }
    }
  }]
};

function drawLinkQ(chart) {
  const width = chart.chart.width;
  const height = chart.chart.height;
  const ctx = chart.chart.ctx;

  ctx.restore();
  const fontSize = (height / 100).toFixed(2);
  ctx.font = `${fontSize}em sans-serif`;
  ctx.fillStyle = labelColor;
  ctx.textBaseline = "middle";

  const text = chart.config.centerText.text;
  const textX = Math.round((width - ctx.measureText(text).width) * 0.5);
  const textY = height / 2;
  ctx.fillText(text, textX, textY);
  ctx.save();
}

window.onload = function() {
  const ctx = document.getElementById("divChartLinkQ").getContext("2d");
  const chart = new Chart(ctx, config);
};
