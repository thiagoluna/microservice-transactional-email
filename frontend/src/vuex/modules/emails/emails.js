import axios from "axios";
import { API_VERSION} from "../../../config/api";

export default {
    state: {
        items: {
            data: []
        }
    },
    mutations: {
        GET_EMAILS (state, emails) {
            state.items.data = emails
        }
    },
    actions: {
        getEmails ({commit}, params) {
            return axios.get(`${API_VERSION}/listemail`, {params})
                .then(response => {
                    commit('GET_EMAILS', response.data)
                })
        },
        sendEmail ({ commit }, params) {
            return axios.post(`${API_VERSION}/sendemail`, params)

        }
    },
    getters: {

    }
}