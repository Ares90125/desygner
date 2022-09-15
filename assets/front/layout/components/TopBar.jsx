import React from "react"
import { useCallback } from "react";
import { useDispatch, useSelector } from "react-redux";
import LocalStorage from "../../global/LocalStorage";
import { sessionInit } from "../../store/reducers/session";
import { sessionSelector } from "../../store/selectors/session";
const TopBar = () => {
    const dispatch = useDispatch();
    const { data: session } = useSelector(sessionSelector);
    const handleLogout = useCallback(() => {
        LocalStorage.removeToken();
        dispatch(sessionInit());
    }, [dispatch, sessionInit])
    return (
        <div className="flex items-center justify-between w-full h-[64px] px-[24px] bg-white shadow-lg fixed z-10">
            <div className="max-w-[200px] md:max-w-full md:h-[64px] cursor-pointer flex justify-center items-center">
                <h1 className="text-xl font-extrabold" style={{ color: '#6758E3' }}>Desygner</h1>
            </div>
            {session &&
                <div className="w-48 flex justify-between">
                    <div className="font-bold text-lg">
                        {session.name}
                    </div>
                    <div onClick={handleLogout} className="cursor-pointer">
                        Logout
                    </div>
                </div>
            }
        </div>
    )
}
export default TopBar;
