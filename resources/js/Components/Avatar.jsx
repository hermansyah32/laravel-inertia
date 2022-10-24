import React, { useState, useRef, useEffect } from "react";
import { useIntersection } from "../Helper/InertsectionObserver";

export default function Avatar({ source, name, role }) {
    const [isInView, setIsInView] = useState(false);
    const imageRef = useRef();

    useIntersection(imageRef, () => {
        if (!source) setIsInView(false);
        else setIsInView(true);
    });

    const getUserInit = () => {
        if (!name) return "";
        const names = name.split(" ");
        if (names.length > 1)
            return names[0].charAt(0) + names[names.length - 1].charAt(0);
        return names[0].charAt(0);
    };

    return (
        <>
            <div className="flex flex-col items-start sm:items-center sm:flex-row">
                <div ref={imageRef}>
                    {isInView ? (
                        <div className="bg-cover rounded-md">
                            <img
                                src={source}
                                alt="user avatar"
                                className="h-8 w-8 rounded-full"
                                onError={() => setIsInView(false)}
                            />
                        </div>
                    ) : (
                        <div className="h-8 w-8 bg-red-400 shadow-md flex justify-center items-center rounded-full">
                            <p className="text-white font-bold text-sm">
                                {getUserInit()}
                            </p>
                        </div>
                    )}
                </div>
                {/* <div className="hidden sm:block text-left">
                    <p className="text-gray-900 text-sm font-medium select-none">
                        {name}
                    </p>
                    <p className="text-gray-800 text-xs select-none">{role}</p>
                </div> */}
            </div>
        </>
    );
}
