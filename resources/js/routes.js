import NotFound from "./components/NotFound";
import Home from "./components/Home";
import GetPolicy from "./components/GetPolicy";
import Login from "./components/Login";
import Blogs from "./components/Blogs";
import Overview from "./components/Overview";
import EditBlog from "./components/EditBlog";
import NewBlog from "./components/NewBlog";
import Users from "./components/Users";
import Register from "./components/Register";
import InsurancePolicies from "./components/InsurancePolicies";
import Participants from "./components/Participants";

export default {
    mode: "history",
    linkActiveClass: "fw-bolder",
    routes: [
        {
            path: "*",
            component: NotFound,
        },
        {
            path: "/",
            component: Home,
            name: "Home",
        },
        {
            path: "/get-policy",
            component: GetPolicy,
            name: "GetPolicy",
        },
        {
            path: "/login",
            component: Login,
            name: "Login",
        },
        {
            path: "/blogs",
            component: Blogs,
            name: "Blogs",
        },
        {
            path: "/overview/:id",
            component: Overview,
            name: "Overview",
        },
        {
            path: "/edit-blog/:id",
            component: EditBlog,
            name: "EditBlog",
        },
        {
            path: "/new-blog",
            component: NewBlog,
            name: "NewBlog",
        },
        {
            path: "/users",
            component: Users,
            name: "Users",
        },
        {
            path: "/register",
            component: Register,
            name: "Register",
        },
        {
            path: "/insurance-policies",
            component: InsurancePolicies,
            name: "InsurancePolicies",
        },
        {
            path: "/participants/:id",
            component: Participants,
            name: "Participants",
        },
    ],
};
