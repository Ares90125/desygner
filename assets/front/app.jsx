import React from 'react';
import { BrowserRouter, Route, Routes } from 'react-router-dom';
import BaseLayout from './layout/BaseLayout';
import AdminHome from './pages/admin/home';
import DeveloperHome from './pages/developer/home';
import EmptyPage from './pages/EmptyPage';
import Login from './pages/Login';
import UserHome from './pages/user/home';
import Root from './root';

const App = () => {
  return (
    <BrowserRouter>
        <Root>
            <Routes>
                <Route path="/login" element={<Login />} />
                <Route path="/" element={<BaseLayout />}>
                    <Route path="/admin/home" element={<AdminHome />} />
                    <Route path="/dev/home" element={<DeveloperHome />} />
                    <Route path="/user/home" element={<UserHome />} />
                    <Route path="/" element={<EmptyPage />} />
                </Route>
            </Routes>
        </Root>
    </BrowserRouter>
  )
}

export default App;
