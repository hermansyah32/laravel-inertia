import React from "react";
import { Head } from "@inertiajs/inertia-react";
import Navbar from "@/Components/Landing/Navbar";
import Footer from "@/Components/Landing/Footer";

export default function Welcome(props) {
    return (
        <>
            <Head title="Inertia" />
            <Navbar auth={props.auth} />

            <div className="xl:px-20 md:px-6 px-4 md:py-20 py-14 w-full">
                <div className="flex flex-col items-center justify-center">
                    <h1 className="lg:text-5xl md:text-4xl text-2xl font-bold leading-6 md:leading-9 lg:leading-10 text-center  ">
                        Mobile Learning
                    </h1>
                    <p className="text-base leading-6 text-center 2xl:w-2/5 md:w-10/12 w-full mt-4">
                        Solusi belajar jarak jauh hemat kuota.
                    </p>
                </div>
                <div className="mt-12">
                    <h2 className="text-xl font-semibold leading-5 mt-12">
                        Visi
                    </h2>
                    <p className="text-base leading-6 mt-6">
                        Mencerdaskan anak bangsa dengan teknologi.
                    </p>
                    <p className="text-base leading-6 mt-4">
                        Mewujudkan Indonesia digital 2050.
                    </p>
                    <h2 className="text-xl font-semibold leading-5 mt-12  ">
                        Hubungi kami
                    </h2>
                    <ul className="list-disc mt-6 ml-6">
                        <li className="text-base  leading-6 md:leading-4  ">
                            me@hermansyah.dev
                        </li>
                    </ul>
                </div>
            </div>

            <Footer />
        </>
    );
}
