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
        getEmails ({ commit }) {
            return axios.get(`${API_VERSION}/listemail`)
                .then(response => {
                    commit('GET_EMAILS', response.data)
                })
                .catch(errors => {
                    console.log(errors)
                })
        }
    },
    getters: {

    }
}