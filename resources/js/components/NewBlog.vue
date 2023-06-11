<template>
    <form>
        <h3 class="m-2">Add new blog!</h3>
        <div class="m-2">
            <label for="title" class="d-block text-lg">Title:</label>
            <input
                type="text"
                class="border border-gray-200 rounded p-2"
                name="title"
            />
        </div>
        <div class="m-2">
            <label for="description" class="d-block text-lg"
                >Description:</label
            >
            <textarea
                class="border border-gray-200 rounded p-2"
                name="description"
                rows="10"
            ></textarea>
        </div>
        <div class="m-2">
            <label for="image" class="d-block text-lg"> Image: </label>
            <input
                type="file"
                class="border border-gray-200 rounded p-2"
                name="image"
            />
        </div>
        <select class="border border-gray-200 rounded p-2 m-2" name="type">
            <option selected value="0">Choose the type of the blog!</option>
            <option value="News">News</option>
            <option value="Post">Post</option>
        </select>
        <div class="m-2">
            <button
                type="submit"
                class="btn btn-success text-white rounded hover:bg-black"
            >
                Create Blog
            </button>
        </div>
    </form>
</template>

<script>
export default {
    data() {
        return {
            formFields: {
                title: "",
                description: "",
                image: "",
                type: "",
            },
        };
    },
    methods: {
        createBlog() {},
    },
};
</script>
