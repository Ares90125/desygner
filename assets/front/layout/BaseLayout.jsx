import React from 'react';
import { Outlet } from 'react-router';
import TopBar from './components/TopBar';

const BaseLayout = () => {
    return (
        <div className='flex min-h-screen'>
            <TopBar />
            <main className='flex-1 mt-[64px] overflow-hidden'>
                <Outlet />
            </main>
        </div>
    )
}

export default BaseLayout;
