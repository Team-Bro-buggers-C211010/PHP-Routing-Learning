import { Outlet } from "react-router"
import Navbar from "./component/shared/Navbar"

const App = () => {
  return (
    <>
      <Navbar />
      <Outlet />
    </>
  )
}

export default App