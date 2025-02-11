<template>
    <div class="mt-[5rem]">
        <GoldenText text="Админ-панель"/>

        <div class="mt-[5rem]">
            <button @click="activatePopup">+ Новая книга</button>
        </div>

        <div class="search flex justify-start mb-[3.75rem] mt-[5rem]">
            <div class="search-box w-1/4 mr-8">
                <p class="text-[1.125rem] mb-2">Поиск по ФИО</p>
                <div class="input flex">
                    <input class="h-[1.75rem] !rounded-[2px] !rounded-r-[0] !rounded-r-0 !py-0" type="text">
                    <div
                        class="search-icon flex justify-center items-center bg-[#E3B661] w-[1.75rem] h-[1.75rem] !rounded-r-[2px]">
                        <Icon type="search"/>
                    </div>
                </div>
            </div>
            <div class="search-box w-1/4">
                <p class="text-[1.125rem] mb-2">Поиск по контактам</p>
                <div class="input flex">
                    <input class="h-[1.75rem] !rounded-[2px] !rounded-r-[0] !py-0" type="text">
                    <div
                        class="search-icon flex justify-center items-center bg-[#E3B661] w-[1.75rem] h-[1.75rem] !rounded-r-[2px]">
                        <Icon type="search"/>
                    </div>
                </div>
            </div>
        </div>

        <table>
            <thead>
            <tr>
                <th>№</th>
                <th>ФИО</th>
                <th>Контакты</th>
                <th>Анкета</th>
                <th>Вход в книгу</th>
                <th>Статус</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in users" :key="user.id">
                <td class="w-[10%]">{{ user.id }}</td>
                <td class="w-[30%]">{{ user.name }}</td>
                <td class="w-[30%]">{{ user.phone }}</td>
                <td class="w-[10%]">
                    <button :disabled="!user.questionnaire?.for_who" @click="showQuestionnairePopup(user.questionnaire)">Анкета</button>
                </td>
                <td class="w-[10%]">
                    <button @click="editBook(user.id)">Войти</button>
                </td>
                <td class="w-[10%]">
                    <Status :status="user.status"/>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="absolute top-0 bottom-0 right-0 left-0 flex justify-center items-center" v-if="popupActive">
            <div class="background absolute w-full h-full bg-black/80 z-9"></div>
            <div class="body p-4 z-10 rounded">
                <input type="text" v-model="form.name" placeholder="ФИО...">
                <input type="text" v-model="form.phone" placeholder="Номер телефона...">
                <input type="password" v-model="form.password" placeholder="Пароль...">
                <div class="flex justify-between">
                    <div class="flex items-center">
                        <input id="cb" type="checkbox" class="mx-4" v-model="form.isAdmin">
                        <label class="text-white" for="cb">Админ</label>
                    </div>
                    <button @click="saveUser">Сохранить</button>
                </div>
            </div>
        </div>

        <div class="absolute top-0 bottom-0 right-0 left-0 flex justify-center items-center" v-if="questionnairePopup">
            <div class="background absolute w-full h-full bg-black/80 z-9"></div>
            <div class="body w-1/2 bg-black p-8 z-10 rounded">
                <div class="flex justify-end"><button @click="closeQuestionnairePopup">Закрыть</button></div>

                <h1 class="text-[#E3B661FF] mb-2">О себе</h1>
                <p class="mb-4"> {{ questionnaire.self_info }}</p>

                <h1 class="text-[#E3B661FF] mb-2">Для кого книга</h1>
                <p class="mb-4">{{ questionnaire.for_who }}</p>

                <h1 class="text-[#E3B661FF] mb-2">О чем будет история</h1>
                <p>{{ questionnaire.summary }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import GoldenText from "@/components/GoldenText.vue";
import Icon from "@/components/Icon.vue";
import Status from "@/components/Admin/Status.vue";
import {useAdminStore} from "@/stores/admin";
import {useUserStore} from "@/stores/user";
import variants from "@/plugins/variants";

export default {
    name: "AdminView",

    components: {Status, Icon, GoldenText},

    data: () => ({
        store: useAdminStore(),
        userStore: useUserStore(),

        users: [],

        popupActive: false,
        questionnairePopup: false,
        questionnaire: null,

        form: {
            name: null,
            phone: null,
            password: null,
            isAdmin: false,
        }
    }),

    methods: {
        async saveUser() {
            await this.store.saveUser(this.form).then(() => {
                this.popupActive = false

            })
        },

        activatePopup() {
            this.form = {
                name: null,
                phone: null,
                password: null,
                isAdmin: false,
            }
            this.popupActive = true
        },

        editBook(id) {
            localStorage.setItem('admin', JSON.stringify({
                bookId: id
            }))
            this.$router.push('/client/book')
        },

        showQuestionnairePopup(questionnaire) {
            let forWho

            for (let v of variants) {
                if (v.value === questionnaire.for_who) {
                    forWho = v.text
                }
            }

            if (!forWho) {
                forWho = questionnaire.for_who
            }

            this.questionnaire = {
                self_info: questionnaire.self_info,
                for_who: forWho,
                summary: questionnaire.summary,
            }

            this.questionnairePopup = true
        },

        closeQuestionnairePopup() {
            this.questionnairePopup = false
            this.questionnaire = null
        }
    },

    async mounted() {
        if (this.userStore.user.type !== 'admin') {
            this.$router.push('/')
        } else {
            await this.store.fetchBooks().then(() => {
                this.users = this.store.books
            })
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.popupActive = false
            }
        })
    }
}
</script>