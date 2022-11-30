// DynamicIconSolid.jsx
// Simple Dynamic HeroIcons Component for React (javascript / jsx)
// by: Mike Summerfeldt (IT-MikeS - https://github.com/IT-MikeS)

import * as HIcons from "@heroicons/react/solid";

const DynamicIconSolid = ({ iconName, className }) => {
    const { ...icons } = HIcons;
    const TheIcon = icons[iconName];

    return (
        <>
            <TheIcon className={className} />
        </>
    );
};

export default DynamicIconSolid;
