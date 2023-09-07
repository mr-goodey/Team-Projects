import jwt from "jsonwebtoken";
import { useRouter } from "next/router";
import { useEffect, useState } from "react";
import $ from "jquery";
import SidebarButton from "@/components/SidebarButton";
import Sidebar from "@/components/Sidebar";
import Head from "next/head";
import axios from "axios";
import { XCircle, CheckCircle, X } from "lucide-react";
import { useCookies } from "react-cookie";

export default function Account(props: any) {
  const [userId, setUserId] = useState();
  const [cookies, setCookie] = useCookies(["token"]);
  const router = useRouter();
  useEffect(() => {
    const token = String(cookies.token);
    try {
      // json parse and stringify to please typescript
      let decoded = JSON.parse(
        JSON.stringify(jwt.verify(token, "your_jwt_secret"))
      );
      // set userId to state
      console.log(token);
      setUserId(decoded.userId);
      // change theme to user selected theme
      $("html").attr("data-theme", decoded.theme);
    } catch (e) {
      // if error with token send user to login page
      router.push("/login");
    }
  }, []);

  const handleSubmit = async (e: any) => {
    e.preventDefault();
    const username = e.target[0].value;
    const theme = e.target[2].value;
    const token = String(cookies.token);
    const res = await axios.put(
      `/api/users/${userId}`,
      { username, theme, token }
    );
    let decoded = JSON.parse(JSON.stringify(jwt.decode(token)));
    decoded.theme = theme;
    const newToken = jwt.sign(decoded, "your_jwt_secret");
    setCookie("token", newToken, { path: "/" });
    if (res.status === 200) {
      $("#success-alert").removeClass("hidden");
    } else {
      $("#error-alert").removeClass("hidden");
    }
  };

  return (
    <>
      <Head>
        <title>Account</title>
        <meta name="description" content="Make it all | Knowledge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" href="/transparent_logo.png" />
      </Head>
      <Sidebar>
        <div className="flex h-screen">
          <div className="flex lg:hidden flex-grow-0 h-full p-2">
            <SidebarButton />
          </div>
          <div className="flex w-full min-h-screen justify-center items-center">
            <form
              className="flex flex-col p-6 w-full mr-2 sm:mr-6 md:w-3/4 2xl:w-1/2 shadow rounded bg-base-300 gap-6"
              onSubmit={(e) => handleSubmit(e)}
            >
              <div
                className="alert alert-success shadow hidden"
                id="success-alert"
              >
                <div>
                  <CheckCircle />
                  <span>Your account details have been updated!</span>
                </div>
                <div className="flex-none">
                  <X
                    className="cursor-pointer"
                    onClick={() => {
                      $("#success-alert").addClass("hidden");
                    }}
                  />
                </div>
              </div>
              <div className="alert alert-error shadow hidden" id="error-alert">
                <div>
                  <XCircle />
                  <span>There was an error updating your account details</span>
                </div>
                <div className="flex-none">
                  <X
                    className="cursor-pointer"
                    onClick={() => {
                      $("#error-alert").addClass("hidden");
                    }}
                  />
                </div>
              </div>
              <span className="text-4xl">Account Settings</span>
              <div className="flex flex-col gap-1">
                <label>Username</label>
                <input
                  type="text"
                  placeholder="Username"
                  className="input w-full"
                  defaultValue={props.user && props.user.username}
                />
              </div>
              <div className="flex flex-col gap-1">
                <label>Email</label>
                <input
                  type="text"
                  placeholder="Email"
                  className="input w-full"
                  defaultValue={props.user && props.user.email}
                  disabled
                />
              </div>
              <div className="flex flex-col gap-1">
                <label>Theme</label>
                <select
                  className="select w-full"
                  onChange={(e) => {
                    $("html").attr("data-theme", e.target.value);
                  }}
                >
                  {props.user && props.user.theme === "light" ? (
                    <>
                      <option value="light" selected>
                        Light
                      </option>
                      <option value="dark">Dark</option>
                    </>
                  ) : (
                    <>
                      <option value="light">Light</option>
                      <option value="dark" selected>
                        Dark
                      </option>
                    </>
                  )}
                </select>
              </div>
              <div className="flex w-full justify-end gap-2">
                <button className="btn btn-success" type="submit">
                  Save
                </button>
              </div>
            </form>
          </div>
        </div>
      </Sidebar>
    </>
  );
}

export async function getServerSideProps(context: any) {
  const token = String(context.req.cookies.token);
  try {
    // json parse and stringify to please typescript
    const decoded = JSON.parse(
      JSON.stringify(jwt.verify(token, "your_jwt_secret"))
    );
    const res = await axios.get(
      `${process.env.NEXT_PUBLIC_HOST}/api/users/${decoded.userId}`,
      { data: { token } }
    );
    if (res.status === 200) {
      return {
        props: {
          user: res.data.data,
        },
      };
    } else {
      return {
        redirect: {
          destination: "/login",
          permanent: false,
        },
      };
    }
  } catch (e) {
    return {
      redirect: {
        destination: "/login",
        permanent: false,
      },
    };
  }
}
