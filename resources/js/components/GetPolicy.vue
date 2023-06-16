<template>
    <form>
        <div class="m-2">
            <label for="forFirstName" class="d-block text-lg"
                >Holders first name</label
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
                >Holders last name</label
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
        <div class="form-check m-2">
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
        <div class="form-check m-2">
            <input
                class="form-check-input"
                type="radio"
                name="policyType"
                id="forGroup"
                @change="forGroup()"
            />
            <label class="form-check-label" for="forGroup">Group</label>
        </div>
        <div v-if="showForm">
            <div v-for="(participant, index) in policy.participants">
                <div class="m-2">
                    <label for="forParticipant" class="d-block text-lg"
                        >Participant first name</label
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
                        >Participant last name</label
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
                    <button
                        @click.prevent="remove(index)"
                        class="btn btn-danger btn-sm"
                    >
                        Remove
                    </button>
                </div>
            </div>
            <div class="m-2">
                <button @click.prevent="add()" class="btn btn-success btn-sm">
                    Add
                </button>
            </div>
        </div>
        <div class="m-2">
            <button @click.prevent="submit()" class="btn btn-primary">
                Submit
            </button>
        </div>
    </form>
</template>

<script>
export default {
    data() {
        return {
            showForm: false,
            policy: {
                holdersFirstName: "",
                holdersLastName: "",
                type: null,
                participants: [
                    {
                        firstName: "",
                        lastName: "",
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
            this.policy.participants.push({
                firstName: "",
                lastName: "",
            });
        },
        remove(index) {
            this.policy.participants.splice(index, 1);
        },
        submit() {
            if (this.policy.type == "Individual") {
                this.policy.participants = [
                    {
                        firstName: "",
                        lastName: "",
                    },
                ];
            }
            console.log(this.policy);
        },
    },
};
</script>
