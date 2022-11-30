import React from "react";

export default function Hero() {
    return (
        <div className="overflow-y-hidden">
            <div className="relative flex justify-center items-center md:flex md:justify-start">
                <img
                    className=" hidden md:block w-full"
                    src="./assets/landing/main-hero.png"
                    alt="welcome image"
                />
                <img
                    className=" md:hidden w-full "
                    src="./assets/landing/main-hero-mobile.png"
                    alt="welcome image"
                />
                <div className="flex absolute justify-start flex-col md:flex-row items-center md:py-10 lg:py-20 xl:py-40 md:space-y-0">
                    <div className=" py-40 sm:py-20  md:hidden"></div>
                    <div className="mt-10 custom sm:mt-96 md:mt-0 h-full flex lg:h-96 px-4 z-10 justify-center items-center md:items-start flex-col md:px-6 xl:px-20">
                        <h1 className="text-xl sm:text-2xl xl:text-4xl text-center md:text-left font-semibold lg:font-normal text-gray-800">
                            Ayo,
                            <br />
                            Gunakan Mobile Learning
                            <br />
                            <span className="lg:font-semibold">
                                Mudah Belajar Dimana Saja
                            </span>
                        </h1>
                        <p className="mt-4 md:w-80 lg:w-3/4 text-center md:text-left  text-base leading-normal text-gray-600">
                            Cukup sekali download, dapat belajar dimana saja
                            tanpa kuota internet. Solusi belajar jarak jauh
                            terkini.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    );
}
