import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue' 
import RegisterView from '../views/Register.vue'
import QuizView from '../views/QuizView.vue'
import DashboardView from '../views/DashboardView.vue'
import CreateQuizView from '../views/CreateQuizView.vue'
import QuizPageView from '../views/QuizPageView.vue'
import QuizResultsView from '../views/QuizResultsView.vue'
import RankingView from '../views/RankingView.vue'
import PrivacyPolicy from '../views/PrivacyPolicy.vue'
import ContactView from '@/views/ContactView.vue'
import QuizzesView from '@/views/QuizzesView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue'),
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
    },
     {
      path: '/register',
      name: 'Register',
      component: RegisterView,
    },
    {
      path: '/quiz/:id',
      name: 'QuizView',
      component: QuizView,
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: DashboardView,
      meta: { requiresAuth: true }
    },
    {
      path: '/create-quiz',
      name: 'create-quiz',
      component: CreateQuizView,
      meta: { requiresAuth: true }
    },
    {
      // path: '/quiz-page',
      path: '/quiz-page/:id',
      name: 'quiz-page',
      component: QuizPageView,
      meta: { requiresAuth: true }
    },
    {
      path: '/quiz-result/:id',
      name: 'quiz-result',
      component: QuizResultsView,
    },
    {
      path: '/quizzes',
      name: 'quizzes',
      component: QuizzesView,
    },    
    {
      path: '/ranking',
      name: 'ranking',
      component: RankingView,
    },
    {
      path: '/privacy',
      name: 'privacy',
      component: PrivacyPolicy,
    },
    {
      path: '/contact',
      name: 'contact',
      component: ContactView,
    },
  ],
})

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  const requiresAuth = to.meta.requiresAuth

  if (requiresAuth && !token) {
    next('/login')
  } else {
    next()
  }
})


export default router
