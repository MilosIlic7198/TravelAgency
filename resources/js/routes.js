import NotFound from "./components/NotFound";
import Home from "./components/Home";
import GetPolicy from "./components/GetPolicy";
import Login from "./components/Login";
import Register from "./components/Register";
import NewBlog from "./components/NewBlog";
import DashBoard from "./components/DashBoard";

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
        },
        {
            path: "/get-policy",
            component: GetPolicy,
        },
        {
            path: "/login",
            component: Login,
        },
        {
            path: "/register",
            component: Register,
        },
        {
            path: "/new-blog",
            component: NewBlog,
        },
        {
            path: "/dashboard",
            component: DashBoard,
            name: "Dashboard",
        },
    ],
};
