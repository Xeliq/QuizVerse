<template>
  <div class="about-container">
    <section class="about-hero">
      <h1 class="about-title">Ranking</h1>
      <div class="charts">
        <div class="charts-wrapper">
          <h2>Average scores</h2>
          <canvas id="lineChart"></canvas>

          <h2>Quizzes per category</h2>
          <canvas id="barChart"></canvas>

          <h2>Top users</h2>
          <canvas id="usersChart"></canvas>

          <h2>Questions per quiz</h2>
          <canvas id="questionsChart"></canvas>

          <h2>Correct vs incorrect answers</h2>
          <canvas id="pieChart"></canvas>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import '../assets/ranking.css'
import '../assets/about.css'
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
            label: "Average score",
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
            label: "Number of quizzes",
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
            label: "Total score",
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
            label: "Number of questions",
            data: data.result.questionsPerQuiz.map(item => item.questions),
            backgroundColor: "rgba(54, 162, 235, 0.6)"
          }]
        }
      });

      // poprawne vs błędne odpowiedzi
      new Chart(document.getElementById("pieChart"), {
        type: "pie",
        data: {
          labels: ["Correct", "Incorrect"],
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
