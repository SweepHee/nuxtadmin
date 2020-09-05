<template>
  <div>
    <title-bar :title-stack="titleStack" />
    <hero-bar>
      {{ $route.params.id ? '회원 수정' : '회원 생성' }}
      <!-- <nuxt-link slot="right" to="/" class="button">
        Dashboard
      </nuxt-link> -->
    </hero-bar>
    <section class="section is-main-section">
      <card-component title="정보" icon="ballot">
        <form @submit.prevent="submit">
          <b-field label="아이디" horizontal>
            <b-field>
              <b-input
                v-model="form.id"
                icon="clipboard-text"
                placeholder="ID"
                name="id"
                required
              />
            </b-field>
          </b-field>
          <b-field label="이름" horizontal>
            <b-field>
              <b-input
                v-model="form.name"
                icon="account"
                placeholder="이름"
                name="name"
                required
              />
            </b-field>
          </b-field>

          <b-field label="비밀번호" horizontal>
            <b-field>
              <b-input
                v-model="form.password"
                type="password"
                icon="lock"
                placeholder="비밀번호"
                name="password"
                required
              />
            </b-field>
          </b-field>

          <b-field label="비밀번호 확인" horizontal>
            <b-field>
              <b-input
                v-model="form.passwordCheck"
                type="password"
                icon="lock"
                placeholder="비밀번호 확인"
                name="password"
                required
              />
            </b-field>
          </b-field>

          <b-field label="이메일" horizontal>
            <b-field>
              <b-input
                v-model="form.email1"
                type="text"
                icon="email"
                placeholder="이메일"
                name="email1"
                required
              />
            </b-field>

            <b-select
              v-model="form.email2"
              placeholder="이메일을 선택해주세요"
              required
            >
              <option
                v-for="(domain, index) in defaultEmails"
                :key="index"
                :value="domain"
              >
                {{ domain }}
              </option>
            </b-select>
          </b-field>

          <hr>
          <b-field label="Subject" message="Message subject" horizontal>
            <b-input
              v-model="form.subject"
              placeholder="e.g. Partnership proposal"
            />
          </b-field>
          <b-field
            label="Question"
            message="Your question. Max 255 characters"
            horizontal
          >
            <b-input
              v-model="form.question"
              type="textarea"
              placeholder="Explain how we can help you"
              maxlength="255"
            />
          </b-field>
          <hr>
          <b-field horizontal>
            <b-field grouped>
              <div class="control">
                <b-button native-type="submit" type="is-primary">
                  Submit
                </b-button>
              </div>
              <div class="control">
                <b-button type="is-primary is-outlined" @click="reset">
                  Reset
                </b-button>
              </div>
            </b-field>
          </b-field>
        </form>
      </card-component>
      <card-component title="Custom elements" icon="ballot-outline">
        <b-field label="Checkbox" class="has-check" horizontal>
          <checkbox-picker
            v-model="customElementsForm.checkbox"
            :options="{ lorem: 'Lorem', ipsum: 'Ipsum', dolore: 'Dolore' }"
            type="is-primary"
          />
        </b-field>
        <hr>
        <b-field label="Radio" class="has-check" horizontal>
          <radio-picker
            v-model="customElementsForm.radio"
            :options="{ one: 'One', two: 'Two' }"
          />
        </b-field>
        <hr>
        <b-field label="Switch" horizontal>
          <b-switch v-model="customElementsForm.switch">
            Default
          </b-switch>
        </b-field>
        <hr>
        <b-field label="File" horizontal>
          <file-picker v-model="customElementsForm.file" />
        </b-field>
      </card-component>
    </section>
  </div>
</template>

<script>
import mapValues from 'lodash/mapValues'
import TitleBar from '@/components/TitleBar'
import CardComponent from '@/components/CardComponent'
import CheckboxPicker from '@/components/CheckboxPicker'
import RadioPicker from '@/components/RadioPicker'
import FilePicker from '@/components/FilePicker'
import HeroBar from '@/components/HeroBar'
export default {
  name: 'Forms',
  components: {
    HeroBar,
    FilePicker,
    RadioPicker,
    CheckboxPicker,
    CardComponent,
    TitleBar
  },
  data () {
    return {
      isLoading: false,
      form: {
        id: '',
        name: '',
        password: '',
        passwordCheck: '',
        email1: '',
        email2: '',
        department: null,
        subject: null,
        question: null
      },
      customElementsForm: {
        checkbox: [],
        radio: null,
        switch: true,
        file: null
      },
      defaultEmails: ['직접입력', '@naver.com', '@gmail.com', '@hanmail.net']
    }
  },
  computed: {
    titleStack () {
      return ['관리자', '회원관리']
    }
  },
  methods: {
    submit () {
      this.$store.dispatch('users/signUp', {
        data: this.form
      })
        .then((res) => {
          console.log(res)
        })
    },
    reset () {
      this.form = mapValues(this.form, (item) => {
        if (item && typeof item === 'object') {
          return []
        }
        return null
      })

      this.$buefy.snackbar.open({
        message: 'Reset successfully',
        queue: false
      })
    }
  },
  head () {
    return {
      title: '관리자 — 회원관리'
    }
  }
}
</script>
