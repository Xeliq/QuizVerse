<template>
  <div class="charts">
    <h1>Statystyki</h1>
    <div class="charts-wrapper">
      <h2>Średnie wyniki</h2>
      <canvas id="lineChart"></canvas>

      <h2>Quizy per kategoria</h2>
      <canvas id="barChart"></canvas>

      <h2>Top użytkownicy</h2>
      <canvas id="usersChart"></canvas>

      <h2>Pytania per quiz</h2>
      <canvas id="questionsChart"></canvas>

      <h2>Poprawne vs błędne odpowiedzi</h2>
      <canvas id="pieChart"></canvas>
    </div>
  </div>
</template>

<script>
import '../assets/ranking.css'
import { onMounted } from "vue";
import { Chart } from "chart.js/auto";
import api from '../axios'

export default {
  name: "ChartsExample",
  props: {
    quizId: {
      type: Number,
      required: true
    }
  },
  setup(props) {
    onMounted(async () => {
      const response = await api.get('/get-ranking-data', {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`
        }
      });
      const data = response.data;

      // średnie wyniki
      new Chart(document.getElementById("lineChart"), {
        type: "line",
        data: {
          labels: data.result.avgScores.map(item => item.quiz),
          datasets: [{
            label: "Średni wynik",
            data: data.result.avgScores.map(item => item.avg_score),
            borderColor: "rgba(75, 192, 192, 1)",
            backgroundColor: "rgba(75, 192, 192, 0.2)",
            fill: true,
            tension: 0.3
          }]
        }
      });

      // quizy per kategoria
      new Chart(document.getElementById("barChart"), {
        type: "bar",
        data: {
          labels: data.result.quizzesPerCategory.map(item => item.category),
          datasets: [{
            label: "Liczba quizów",
            data: data.result.quizzesPerCategory.map(item => item.count),
            backgroundColor: "rgba(153, 102, 255, 0.6)"
          }]
        }
      });

      // top użytkownicy
      new Chart(document.getElementById("usersChart"), {
        type: "bar",
        data: {
          labels: data.result.topUsers.map(item => item.user),
          datasets: [{
            label: "Łączny wynik",
            data: data.result.topUsers.map(item => item.score),
            backgroundColor: "rgba(255, 159, 64, 0.6)"
          }]
        }
      });

      // pytania per quiz
      new Chart(document.getElementById("questionsChart"), {
        type: "bar",
        data: {
          labels: data.result.questionsPerQuiz.map(item => item.quiz),
          datasets: [{
            label: "Liczba pytań",
            data: data.result.questionsPerQuiz.map(item => item.questions),
            backgroundColor: "rgba(54, 162, 235, 0.6)"
          }]
        }
      });

      // poprawne vs błędne odpowiedzi
      new Chart(document.getElementById("pieChart"), {
        type: "pie",
        data: {
          labels: ["Poprawne", "Błędne"],
          datasets: [{
            data: [
              data.result.answersStats.correct,
              data.result.answersStats.incorrect
            ],
            backgroundColor: ["rgba(75, 192, 192, 0.6)", "rgba(255, 99, 132, 0.6)"]
          }]
        }
      });
    });
  }
};
</script>
