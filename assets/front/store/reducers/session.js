import { createSlice } from "@reduxjs/toolkit";
import LocalStorage from "../../global/LocalStorage";

const initialState = {
  loading: false,
  initial: true,
  data: null,
  error: null,
}

const sessionSlice = createSlice({
  name: 'session',
  initialState,
  reducers: {
    signOut() {
      LocalStorage.removeToken();
      return { ...initialState, initial: false };
    },
    sessionInit() {
      return { ...initialState };
    },
    setSession(state, action) {
      const { payload } = action;
      return { ...state, data: payload };
    },
    setInitial(state, action) {
      const { payload } = action;
      return { ...state, data: payload };
    }
  }
});

const sessionReducer = sessionSlice.reducer;
export default sessionReducer;

export const { signOut, sessionInit, setSession, setInitial: setSessionInitial } = sessionSlice.actions;
