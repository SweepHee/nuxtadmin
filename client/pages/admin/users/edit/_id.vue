<template>
  <div>
    <title-bar :title-stack="titleStack" />
    <hero-bar>
      {{ $route.params.id ? '회원 수정' : '회원 생성' }}
    </hero-bar>
    <section class="section is-main-section">
      <card-component title="정보" icon="ballot">
        <form @submit.prevent="submit">
          <b-field
            label="아이디"
            horizontal
            :type="idInputStatus.type"
            :message="idInputStatus.message"
          >
            <b-field>
              <b-input
                v-if="$route.params.id"
                v-model="form.id"
                icon="clipboard-text"
                placeholder="ID"
                name="id"
                readonly
                disabled
                required
              />
              <b-input
                v-if="!$route.params.id"
                ref="id"
                v-model="form.id"
                icon="clipboard-text"
                placeholder="ID"
                name="id"
                required
                max-length="20"
                @input="onIdDuplicateCheck"
                @blur="getIdDuplicateStatus"
              />
            </b-field>
          </b-field>
          <b-field label="이름" horizontal>
            <b-field>
              <b-input
                ref="name"
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
                ref="password"
                v-model="form.password"
                type="password"
                icon="lock"
                placeholder="비밀번호"
                name="password"
                password-reveal
                required
              />
            </b-field>
          </b-field>

          <b-field label="비밀번호 확인" horizontal>
            <b-field>
              <b-input
                ref="passwordCheck"
                v-model="form.passwordCheck"
                type="password"
                icon="lock"
                placeholder="비밀번호 확인"
                name="password"
                password-reveal
                required
              />
            </b-field>
          </b-field>

          <b-field label="이메일" horizontal>
            <b-field>
              <b-input
                ref="email1"
                v-model="form.email1"
                type="text"
                icon="email"
                placeholder="이메일"
                name="email1"
                required
              />
            </b-field>

            <b-select
              ref="email2"
              v-model="form.email2"
              placeholder="이메일을 선택해주세요"
              required
            >
              <option
                v-for="(domain, index) in defaultEmails"
                :key="index"
                :value="index !== 0 ? domain : ' ' "
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
    {{ created }}
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
      idInputStatus: {
        type: '',
        message: ''
      },
      defaultEmails: ['직접입력', '@naver.com', '@gmail.com', '@hanmail.net']
    }
  },
  computed: {
    titleStack () {
      return ['관리자', '회원관리']
    },
    created () {
      const created = this.$store.state.users.create
      if (created.code === 1) {
        this.$buefy.dialog.confirm({
          title: '알림',
          message: '계정이 생성되었습니다. 목록으로 돌아가시겠습니까?',
          onConfirm: () => {
            this.$store.commit("users/createUser", [])
            this.$router.push("/admin/users")
          },
          onCancel: () => {
            this.$store.commit("users/createUser", [])
            window.location.reload(true)
          }
        })
      } else if (created.code === 3) {
        this.$buefy.snackbar.open({
          message: created.msg,
          type: 'is-danger',
          duration: 5000,
          position: 'is-top',
          queue: false,
          onAction: () => {
            this.$refs.email1.focus()
          }
        })
      }
      this.$store.commit("users/createUser", [])
      return ''
    }
  },
  methods: {
    submit () {
      const form = this.form
      const fail = {}
      if (form.id === '') {
        fail.msg = '아이디를 입력해주세요.'
        this.$refs.id.focus()
      }

      if (form.password === '') {
        fail.msg = '비밀번호를 입력해주세요.'
        this.$refs.password.focus()
      }

      if (form.passwordCheck === '') {
        fail.msg = '비밀번호 확인을 입력해주세요.'
        this.$refs.passwordCheck.focus()
      }

      if (form.email1 === '') {
        fail.msg = '이메일을 입력해주세요.'
        this.$refs.email1.focus()
      }

      if (form.email2 === '') {
        fail.msg = '이메일을 정확히 입력해주세요.'
        this.$refs.email2.focus()
      }

      if (form.password !== form.passwordCheck) {
        fail.msg = '비밀번호 확인이 틀렸습니다'
        this.$refs.password.focus()
      }
      if (fail.msg !== undefined) {
        this.$buefy.snackbar.open({
          message: fail.msg,
          type: 'is-danger',
          duration: 5000,
          position: 'is-top',
          queue: false
        })
        return false
      }

      this.$store.dispatch('users/signUp', {
        data: this.form
      })
    },
    onIdDuplicateCheck () {
      this.$store.dispatch('users/isUser', {
        type: 'id',
        data: this.form.id
      })
        .catch((err) => {
          console.log(err)
          return false
        })
    },
    getIdDuplicateStatus () {
      if (this.form.id === '') {
        return false
      }

      const regType = /^[A-za-z0-9]{5,20}$/
      if (!regType.test(this.form.id)) {
        this.$buefy.snackbar.open({
          message: '아이디가 조건에 맞지 않습니다. <br>영문 소대문자, 숫자를 조합해서 5자에서 20자 길이로 만들어주세요',
          type: 'is-danger',
          duration: 5000,
          position: 'is-top',
          queue: false,
          onAction: () => {
            this.$refs.id.focus()
          }
        })
        this.idInputStatus.type = "is-danger"
        this.idInputStatus.message = "영문 소대문자, 숫자를 조합해서 5자에서 20자 길이로 만들어주세요."
        return false
      }

      const status = this.$store.state.users.idDuplicate
      if (status.data) {
        this.$buefy.snackbar.open({
          message: '아이디가 중복됩니다!',
          type: 'is-danger',
          queue: false
        })
        this.idInputStatus.type = "is-danger"
        this.idInputStatus.message = "아이디가 중복됩니다."
      } else {
        this.$buefy.snackbar.open({
          message: '사용할 수 있는 아이디입니다!',
          queue: false
        })
        this.idInputStatus.type = "is-success"
        this.idInputStatus.message = "사용할 수 있는 아이디입니다."
      }
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
