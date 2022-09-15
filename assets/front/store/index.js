import { combineReducers, configureStore } from "@reduxjs/toolkit";
import sessionReducer from "./reducers/session";

const combinedReducers = combineReducers({
  session: sessionReducer
})
const store = configureStore({
  reducer: (state, action) => {
    return combinedReducers(state, action);
  }
});

export default store;
