import React, { useState } from "react";

export const Content = () => {
    return (
        <>
            <div className="xl:px-20 md:px-6 px-4 md:py-20 py-14 w-full flex flex-col items-center justify-center">
                <h1 className="text-gray-900 lg:text-4xl md:text-2xl text-xl lg:leading-9 md:leading-6 leading-4 font-semibold leading-10">
                    Keunggulan Mobile Learning
                </h1>
                <p className="text-base leading-6 mt-4 text-center text-gray-900 lg:w-4/12 w-8/12">
                    Solusi bagi siswa untuk belajar mandiri tanpa khawatir
                    kehilangan koneksi internet.
                </p>
                <div className="grid sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-x-24 gap-y-11 mt-11">
                    <div className="flex flex-col md:items-start items-center md:justify-start justify-center">
                        <img
                            src="https://tuk-cdn.s3.amazonaws.com/can-uploader/svg-vector-1.svg"
                            className="w-14 h-14"
                            alt="Vector-1"
                        />
                        <p className="text-base font-bold leading-4 mt-6">
                            Hemat kuota
                        </p>
                        <p className="w-72 lg:text-base text-sm lg:leading-6 leading-5 mt-4 text-gray-900 md:text-left text-center">
                            Mobile learning tidak harus menggunakan kuota
                            internet selama digunakan tentu ini akan menghemat
                            kuota internet yang digunakan.
                        </p>
                    </div>
                    <div className="flex flex-col md:items-start items-center md:justify-start justify-center">
                        <img
                            src="https://tuk-cdn.s3.amazonaws.com/can-uploader/svg-vector-2.png"
                            className="w-14 h-14"
                            alt="Vector-1"
                        />
                        <p className="text-base font-bold leading-4 mt-6 text-gray-900">
                            Sinkronisasi Otomatis
                        </p>
                        <p className="w-72 lg:text-base text-sm lg:leading-6 leading-5 mt-4 text-gray-900 md:text-left text-center">
                            Mobile learning akan mensinkronkan data aplikasi
                            dengan server ketika internet ada.
                        </p>
                    </div>
                    <div className="flex flex-col md:items-start items-center md:justify-start justify-center">
                        <img
                            src="https://tuk-cdn.s3.amazonaws.com/can-uploader/svg-vector-3.png"
                            className="w-14 h-14"
                            alt="Vector-1"
                        />
                        <p className="text-base font-bold leading-4 mt-6 text-gray-900">
                            Dimanapun dan Kapanpun
                        </p>
                        <p className="w-72 lg:text-base text-sm lg:leading-6 leading-5 mt-4 text-gray-900 md:text-left text-center">
                            Tanpa ketergantungan dengan koneksi internet,
                            aplikasi dapat digunakan dimanapun dan kapanpun.
                        </p>
                    </div>
                </div>
            </div>
        </>
    );
};
