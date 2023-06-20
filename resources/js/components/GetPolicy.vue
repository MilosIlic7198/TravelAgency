<template>
    <form>
        <div class="row justify-content-sm-center">
            <h3 class="m-2">Get Policy!</h3>
            <div class="col-sm-auto">
                <div class="m-2">
                    <label for="forFirstName" class="d-block text-lg"
                        >Holders first name:</label
                    >
                    <input
                        type="text"
                        class="border border-gray-200 rounded p-2"
                        id="forFirstName"
                        placeholder="Enter first name"
                        name="firstName"
                        v-model="policy.holdersFirstName"
                    />
                </div>
                <div class="m-2">
                    <label for="forLastName" class="d-block text-lg"
                        >Holders last name:</label
                    >
                    <input
                        type="text"
                        class="border border-gray-200 rounded p-2"
                        id="forLastName"
                        placeholder="Enter last name"
                        name="lastName"
                        v-model="policy.holdersLastName"
                    />
                </div>
                <div class="m-2">
                    <label for="forPhoneNumber" class="d-block text-lg"
                        >Holders phone number:</label
                    >
                    <input
                        type="text"
                        class="border border-gray-200 rounded p-2"
                        id="forPhoneNumber"
                        placeholder="Enter phone number"
                        name="phoneNumber"
                        v-model="policy.holdersPhoneNumber"
                    />
                </div>
            </div>
            <div class="col-sm-auto m-2">
                <label for="description" class="d-block text-lg">
                    Description:
                </label>
                <textarea
                    class="border border-gray-200 rounded p-2"
                    name="description"
                    rows="5"
                    v-model="policy.description"
                ></textarea>
            </div>
            <div class="col-sm-auto">
                <div class="m-2">
                    <label for="forDateFrom" class="d-block text-lg"
                        >Date from:</label
                    >
                    <date-picker
                        type="date"
                        v-model="policy.dateFrom"
                        format="DD.MM.YYYY"
                    ></date-picker>
                </div>
                <div class="m-2">
                    <label for="forDateTo" class="d-block text-lg"
                        >Date to:</label
                    >
                    <date-picker
                        type="date"
                        v-model="policy.dateTo"
                        format="DD.MM.YYYY"
                    ></date-picker>
                </div>
            </div>
        </div>
        <div class="row justify-content-sm-center" v-if="showForm">
            <div
                class="col-sm-auto"
                v-for="(participant, index) in policy.participants"
            >
                <div class="m-2">
                    <label for="forParticipant" class="d-block text-lg"
                        >Participant first name:</label
                    >
                    <input
                        type="text"
                        class="border border-gray-200 rounded p-2"
                        id="forParticipant"
                        placeholder="Enter first name"
                        name="participantFirstName"
                        v-model="participant.firstName"
                    />
                </div>
                <div class="m-2">
                    <label for="forParticipant" class="d-block text-lg"
                        >Participant last name:</label
                    >
                    <input
                        type="text"
                        class="border border-gray-200 rounded p-2"
                        id="forParticipant"
                        placeholder="Enter last name"
                        name="participantLastName"
                        v-model="participant.lastName"
                    />
                </div>
                <div class="m-2">
                    <label for="forParticipant" class="d-block text-lg"
                        >Participant birthdate:</label
                    >
                    <date-picker
                        type="date"
                        v-model="participant.birthdate"
                        format="DD.MM.YYYY"
                    ></date-picker>
                </div>
                <div class="m-2">
                    <button
                        @click.prevent="remove(index)"
                        class="btn btn-danger btn-sm"
                    >
                        Remove
                    </button>
                </div>
            </div>
        </div>
        <div class="row justify-content-sm-center" v-if="showForm">
            <div class="col-sm-auto m-2">
                <button @click.prevent="add()" class="btn btn-success btn-sm">
                    Add
                </button>
            </div>
        </div>
        <div class="row justify-content-sm-center">
            <div class="col-sm-auto m-2">
                <div class="form-check">
                    <input
                        class="form-check-input"
                        type="radio"
                        name="policyType"
                        id="forIndividual"
                        @change="forIndividual()"
                        :checked="showForm == false"
                    />
                    <label class="form-check-label" for="forIndividual"
                        >Individual</label
                    >
                </div>
                <div class="form-check">
                    <input
                        class="form-check-input"
                        type="radio"
                        name="policyType"
                        id="forGroup"
                        @change="forGroup()"
                    />
                    <label class="form-check-label" for="forGroup">Group</label>
                </div>
                <div class="m-2">
                    <button @click.prevent="submit()" class="btn btn-primary">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

export default {
    components: { DatePicker },
    data() {
        return {
            showForm: false,
            policy: {
                type: "Individual",
                description: "",
                holdersFirstName: "",
                holdersLastName: "",
                holdersPhoneNumber: "",
                dateFrom: "",
                dateTo: "",
                participants: [
                    {
                        firstName: "",
                        lastName: "",
                        birthdate: "",
                    },
                ],
            },
        };
    },
    methods: {
        forIndividual() {
            this.policy.type = "Individual";
            this.showForm = false;
        },
        forGroup() {
            this.policy.type = "Group";
            this.showForm = true;
        },
        add() {
            if (
                this.policy.participants[this.policy.participants.length - 1]
                    .firstName == "" ||
                this.policy.participants[this.policy.participants.length - 1]
                    .lastName == "" ||
                this.policy.participants[this.policy.participants.length - 1]
                    .birthdate == ""
            ) {
                alert("You must fill out last participant to add more!");
                return;
            }
            this.policy.participants.push({
                firstName: "",
                lastName: "",
                birthdate: "",
            });
        },
        remove(index) {
            this.policy.participants.splice(index, 1);
        },
        submit() {
            if (
                this.policy.description == "" ||
                this.policy.holdersFirstName == "" ||
                this.policy.holdersLastName == "" ||
                this.policy.holdersPhoneNumber == "" ||
                this.policy.dateFrom == "" ||
                this.policy.dateTo == ""
            ) {
                alert("You must fill out all fields!");
                return;
            }
            if (this.policy.type == "Individual") {
                this.policy.participants = [
                    {
                        firstName: "",
                        lastName: "",
                        birthdate: "",
                    },
                ];
            } else {
                for (let i = 0; i < this.policy.participants.length; i++) {
                    if (
                        this.policy.participants[i].firstName == "" ||
                        this.policy.participants[i].lastName == "" ||
                        this.policy.participants[i].birthdate == ""
                    ) {
                        alert("You must fill out participants!");
                        return;
                    }
                }
            }
            axios.post("/api/buy-policy", this.policy).then((res) => {
                const status = JSON.parse(res.status);
                if (status == "200") {
                    alert(res.data.success);
                }
            });
        },
    },
};
</script>
