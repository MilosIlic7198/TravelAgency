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
            />
        </div>
        <div class="form-check m-2">
            <input
                class="form-check-input"
                type="radio"
                name="policyType"
                id="forIndividual"
                v-model="showFirst"
                :value="false"
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
                v-model="showFirst"
                :value="true"
            />
            <label class="form-check-label" for="forGroup">Group</label>
        </div>
        <div v-if="showFirst">
            <div v-for="key in count" :key="key">
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
                    />
                </div>
            </div>
            <div class="m-2">
                <a href="#" class="m-1" id="addFields"
                    ><i class="fa fa-plus"></i
                ></a>
                <a href="#" class="m-1" id="removeFields"
                    ><i class="fa fa-minus"></i
                ></a>
            </div>
        </div>
        <div class="m-2">
            <button class="btn btn-success">Show Values!</button>
        </div>
    </form>
</template>

<script>
export default {
    data() {
        return {
            showFirst: null,
            count: 1,
            participants: [],
        };
    },
};
</script>
