import React from "react";
import ReactDOM from "react-dom/client";
import { BrowserRouter as Router } from "react-router-dom";
import './scss/app.scss';
import Home from './components/Home';

const root = ReactDOM.createRoot(
  document.getElementById('root')
);
root.render(
  <Router>
    <Home />
  </Router>
)
