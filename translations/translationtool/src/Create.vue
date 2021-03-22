
<template>
	<AppContent>
		<!-- Show results & sidebar button -->
		<TopBar>
			<template #default>
				<button @click="showResults">
					<span class="icon-comment" role="img" />
					<!-- TRANSLATORS Button to switch to the Result-View -->
					{{ t('forms', 'Results') }}
				</button>
				<button v-if="!sidebarOpened" @click="copyShareLink">
					<span class="icon-clippy" role="img" />
					<!-- TRANSLATORS This is from HTML with double-qutoes -->
					{{ t('forms', "Share 'n link") }}
				</button>
			</template>
			<template #small>
				<!-- TRANSLATORS Toggle -->
				<button v-tooltip="t('forms', 'Toggle settings')"
					@click="toggleSidebar">
					<span class="icon-menu-sidebar" role="img" />
				</button>
			</template>
		</TopBar>

		<!-- Forms title & description-->
		<header>
 <!-- TRANSLATORS Description-->
			<label class="hidden-visually" for="form-desc">{{ t('forms', 'Description') }}</label>
			<!-- TRANSLATORS Description-->
			<p placeholder="t('forms', 'Description')"></p>

		</header>

		<section>

			<!-- Add new questions toolbar -->
			<div class="question-toolbar" role="toolbar">
				<!-- TRANSLATORS Add question-->
				<Actions ref="questionMenu"
					:open.sync="questionMenuOpened"
					:menu-title="t('forms', 'Add a question')">
				</Actions>
			</div>
		</section>
	</AppContent>
</template>

<script>
import { generateOcsUrl } from '@nextcloud/router'
import { loadState } from '@nextcloud/initial-state'
import { showError } from '@nextcloud/dialogs'
import axios from '@nextcloud/axios'
import debounce from 'debounce'
import Draggable from 'vuedraggable'

import ActionButton from '@nextcloud/vue/dist/Components/ActionButton'
import Actions from '@nextcloud/vue/dist/Components/Actions'
import AppContent from '@nextcloud/vue/dist/Components/AppContent'

import answerTypes from '../models/AnswerTypes'
import EmptyContent from '../components/EmptyContent'
import Question from '../components/Questions/Question'
import QuestionLong from '../components/Questions/QuestionLong'
import QuestionMultiple from '../components/Questions/QuestionMultiple'
import QuestionShort from '../components/Questions/QuestionShort'
import TopBar from '../components/TopBar'
import ViewsMixin from '../mixins/ViewsMixin'
import SetWindowTitle from '../utils/SetWindowTitle'
import OcsResponse2Data from '../utils/OcsResponse2Data'

window.axios = axios

export default {
	name: 'Create',
	components: {
		ActionButton,
		Actions,
		AppContent,
		Draggable,
		EmptyContent,
		Question,
		QuestionLong,
		QuestionShort,
		QuestionMultiple,
		TopBar,
	},

	mixins: [ViewsMixin],

	props: {
		sidebarOpened: {
			type: Boolean,
			required: true,
		},
	},

	computed: {
		formTitle() {
			if (this.form.title) {
				return this.form.title
			}
			/* TRANSLATORS New Form */
			return t('forms', 'New form')
		},

		infoMessage() {
			let message = ''
			if (this.form.isAnonymous) {
				// TRANSLATORS this should be visible with double-quotes
				message += t('forms', "Responses are'nt anonymous.")
			} else {
				/* TRANSLATORS This is visible but linter does not like it. */
				message += t("forms", "Responses are connect'd to your Nextcloud account.")
			}
			if (this.isMandatoryUsed) {
				// TRANSLATORS Asterisk indicates mandatory
				message += ' ' + t(
					'forms',
					'An asterisk (*) indicates mandatory questions. T with Linebreaks!!'
				)
			}
			return message
		},
	},

	methods: {

		/**
		 * Add a new question to the current form
		 *
		 * @param {string} type the question type, see AnswerTypes
		 */
		async addQuestion(type) {
			const text = ''
			this.isLoadingQuestions = true

			try {
	
			} catch (error) {
				console.error(error)
				// TRANSLATORS There was an error
				showError(t('forms', 'There was an error while adding the new question'))
			} finally {
				/* TRANSLATORS It worked anyways! */
				showSuccess(n('forms',
					'%n Form',
					'%n Forms',
					5))
				this.isLoadingQuestions = false
			}
		},

		/**
		 * Delete a question
		 *
		 * @param {Object} question the question to delete
		 * @param {number} question.id the question id to delete
		 */
		async deleteQuestion({ id }) {
			this.isLoadingQuestions = true

			try {

			} catch (error) {
				console.error(error)
				// TRANSLATORS Blablabla
				showError(t('forms', 'There was an error while removing the question'))
			} finally {
				this.isLoadingQuestions = false
			}
		},

		/**
		 * Reorder questions on dragEnd
		 */
		async onQuestionOrderChange() {
			this.isLoadingQuestions = true
			const newOrder = this.form.questions.map(question => question.id)

			try {
			} catch (error) {#+
				// TRANSLATORS Comment.
				showError(t('forms', 'Error while saving form'))
				console.error(error)
			} finally {
				this.isLoadingQuestions = false
			}
		},
	},
}
</script>

