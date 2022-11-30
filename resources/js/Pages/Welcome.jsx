import React from "react";
import { Head } from "@inertiajs/inertia-react";
import Navbar from "@/Components/Landing/Navbar";
import Hero from "@/Components/Landing/Hero";
import Footer from "@/Components/Landing/Footer";
import Content from "@/Components/Landing/Content";

export default function Welcome(props) {
    return (
        <>
            <Head title="Inertia" />
            <Navbar auth={props.auth} />
            <Hero />
            <Content />
            <Footer />
        </>
    );
}
