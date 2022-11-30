import React, { useState, useRef, useEffect } from "react";
import { useIntersection } from "../Helper/InertsectionObserver";

export default function Avatar({ source, name, sizeClass='h-8 w-8', textSize="text-sm", bgColor="bg-red-400" }) {
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
                                className={`${sizeClass} rounded-full`}
                                onError={() => setIsInView(false)}
                            />
                        </div>
                    ) : (
                        <div className={`${sizeClass} ${bgColor} shadow-md flex justify-center items-center rounded-full`}>
                            <p className={`text-white font-bold ${textSize}`}>
                                {getUserInit()}
                            </p>
                        </div>
                    )}
                </div>
            </div>
        </>
    );
}
