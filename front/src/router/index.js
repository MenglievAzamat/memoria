import {createRouter, createWebHistory} from 'vue-router';

const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('@/views/HomeView.vue'),
    children: [
      {
        path: 'client',
        name: 'client',
        component: () => import('@/views/ClientView.vue')
      },
      {
        path: 'client/questionnaire',
        name: 'questionnaire',
        component: () => import('@/views/Client/QuestionnaireView.vue')
      },

      {
        path: 'admin',
        name: 'admin',
        component: () => import('@/views/AdminView.vue')
      },
    ]
  },

  {
    path: '/client/book',
    name: 'book',
    component: () => import('@/views/BookView.vue'),
    children: [
      {
        path: 'cover',
        name: 'cover',
        component: () => import('@/views/Book/CoverView.vue')
      },
      {
        path: 'title',
        name: 'title',
        component: () => import('@/views/Book/TitleView.vue')
      },
      {
        path: 'chapters/:id',
        name: 'chapter',
        component: () => import('@/views/Book/ChapterView.vue')
      },
      {
        path: 'review',
        name: 'review',
        component: () => import('@/views/Book/ReviewView.vue')
      },
      {
        path: 'pdf',
        name: 'pdf',
        component: () => import('@/views/Book/PDFGenerateView.vue')
      },
    ]
  },

  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/AuthView.vue')
  },

  // {
  //   path: '/:pathMatch(.*)*',
  //   name: '404',
  //   redirect: '/'
  // },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
