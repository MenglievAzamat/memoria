<template>
    <div class="mt-[4.1875rem]">
        <div class="intro mb-[3.25rem]">
            <GoldenText text="Моя анкета. Вводные данные"/>
        </div>

        <div class="form">
            <h2>Данные</h2>
            <p class="mb-[.75rem]">Напишите пожалуйста ваши ФИО, возраст, место рождения, место жительства, профессия,
                род деятельности на
                данный момент:</p>
            <textarea v-model="form.self_info" placeholder="Введите текст..."></textarea>

            <div class="w-full h-[2px] bg-[#E3B661] opacity-20 rounded mt-[2.9375rem] mb-[2.1875rem]"></div>

            <h2>Для кого мы пишем эту книгу?</h2>
            <div class="variants flex flex-wrap justify-between items-center">
                <div class="variant flex w-1/4 mb-[.6875rem]" v-for="variant in variants">
                    <input
                        name="variant"
                        type="radio"
                        class="mr-2"
                        :key="variant.value"
                        :value="variant.value"
                        v-model="form.for_who"
                    >
                    <p>{{ variant.text }}</p>
                </div>
                <input
                    type="text"
                    v-model="form.other"
                    v-if="form.for_who === 'other'"
                    placeholder="Другое"
                >
            </div>

            <div class="w-full h-[2px] bg-[#E3B661] opacity-20 rounded mt-[2.9375rem] mb-[2.1875rem]"></div>

            <h2>О чем будет история</h2>
            <p class="mb-2">Поделитесь ниже, о чем будет история (истории), которую вы бы хотели сохранить в своей
                книге: какие факты
                из жизни хотели бы сохранить, значимые события, имена, воспоминания. Опишите своими словами
                в 2-3 предложениях или тезисно, но постарайтесь вспомнить минимум 3 важных события, истории или
                периоды вашей жизни.</p>
            <p class="text-[#FFE8BD] opacity-30 mb-5">Возможно, вам помогут «шпаргалки» ниже:<br>
                Я хочу сохранить свои воспоминания: о детстве; о родителях; о друзьях;
                о творчестве; о детях; о взрослении; о свадьбе; о беременности; о достижениях; о трудных или переломных
                моментах; об отношениях в семье; о взаимопонимании; про семейные ценности; о предках (про бабушек и
                дедушек); о студенчестве и тд.</p>
            <textarea v-model="form.summary" placeholder="Введите текст..."></textarea>
            <p class="mb-[2.875rem] text-right">{{ form.summary?.length }}</p>

            <button class="mb-[12.625rem]" @click="submitQuestionnaire">Отправить данные</button>
        </div>
    </div>
</template>

<script>
import GoldenText from "@/components/GoldenText.vue";
import {useBookStore} from "@/stores/book";
import variants from "@/plugins/variants";

export default {
    name: "QuestionnaireView",

    components: {GoldenText},

    data: () => ({
        store: useBookStore(),

        form: {
            book_id: null,
            self_info: null,
            for_who: null,
            summary: null,
            other: null,
        },

        variants: variants
    }),

    methods: {
        submitQuestionnaire() {
            if (this.form.summary.length < 400) {
                alert('Поле "О чем будет история" должно содержать не менее 400 символов (включая пробелы)')
            } else {
                if (this.form.other) {
                    this.form.for_who = this.form.other
                }

                this.store.upsertQuestionnaire(this.store.book.id, this.form)
            }
        }
    },

    watch: {
        'form.for_who'() {
            if (this.form.for_who !== 'other') {
                this.form.other = null
            }
        }
    },


    mounted() {
        let questionnaire = this.store.book?.questionnaire

        if (questionnaire) {
            this.form = {
                self_info: questionnaire.self_info,
                for_who: questionnaire.for_who,
                summary: questionnaire.summary,
            }

            if (!['family', 'all', 'future', 'present', 'me', 'legacy', 'reflection', 'other'].includes(questionnaire.for_who)) {
                this.form.for_who = 'other'
                this.form.other = questionnaire.for_who
            }
        }
    }
}
</script>

<style scoped>
h2 {
    @apply text-white text-[1rem] font-bold mb-[.5rem];
}
</style>