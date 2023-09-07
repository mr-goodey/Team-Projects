import Sidebar from "@/components/Sidebar";
import Head from "next/head";
import { useEffect, useState } from "react";
import $ from "jquery";
import React from "react";
import SidebarButton from "@/components/SidebarButton";

import ProjectDetailsProjectTile from "@/components/ProjectDetailsTile";
import ProjectSelectBarTile from "@/components/ProjectSelectBar";
import ProjectStatisticsTile from "@/components/ProjectStatisticsTile";
import ProjectTaskBreakdown from "@/components/ProjectTaskBreakdownTile";
import axios from "axios";

interface Project {
  id: number;
  name: string;
  description: string;
  deadline: string;
  leaderId: number;
  tasks: any[];
}

export default function Analysis() {
  const [currentProject, setCurrentProject] = useState(0);

  const [currentEmployee, setCurrentEmployee] = useState("All");

  useEffect(() => {
    // all the components listen to the state changes that are brought about by the selects changing
    const projectSelect = $("#projectSelect");
    projectSelect.on("change", function () {
      // handle project change
      // console.log("Project changed");
      // console.log($(this).val());
      const selectedProject = $(this).val();
      if (selectedProject == undefined) {
        setCurrentProject(0); //  id for placeholder project
      } else {
        setCurrentProject(parseInt(selectedProject as string));
      }
    });

    const employeeSelect = $("#employeeSelect");
    employeeSelect.on("change", function () {
      // handle employee change
      // console.log("Emp changed");
      // console.log($(this).val());
      const selectedEmployee = $(this).val();
      if (selectedEmployee === undefined) {
        setCurrentEmployee("All");
      } else {
        setCurrentEmployee(selectedEmployee as string);
      }
    });

    setCurrentEmployee("all");

    // Cleanup event listeners when component unmounts
    return () => {
      projectSelect.off("change");
      employeeSelect.off("change");
    };
  }, []);

  return (
    <>
      <Head>
        <title>Make-It-All | Analysis</title>
        <meta name="description" content="Make it all analytics page" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" href="/transparent_logo.png" />
      </Head>
      <Sidebar>
        <div className="flex h-screen">
          <div className="flex lg:hidden flex-grow-0 h-full p-2">
            <SidebarButton />
          </div>

          <div className="flex flex-col w-full h-full bg-base-100">
            <ProjectSelectBarTile />
            <div className=" w-full h-full">
              <div className=" w-full  max-h-72 flex flex-row">
                <ProjectDetailsProjectTile currentProject={currentProject} />

                <ProjectTaskBreakdown
                  currentProject={currentProject}
                  currentEmployee={currentEmployee}
                />
              </div>
              <ProjectStatisticsTile
                currentProject={currentProject}
                currentEmployee={currentEmployee}
              />
            </div>
          </div>
        </div>
      </Sidebar>
    </>
  );
}

/* 
  NOTE FROM TEGID:
  You guys should probably look into using getServerSideProps() instead of useEffect() to fetch data from the API.
  Its generally just easier to use and you don't have to worry about useEffect() cuz it runs on the server.
  Here's the docs: https://nextjs.org/docs/pages/building-your-application/data-fetching/get-server-side-props
  If you want any help lmk
 */

//Gets all tasks
export async function getTasksByApi() {
  const res = await axios.get(`/api/tasks/`);
  return res.data;
}

//Gets all projects
export async function getProjectsByApi() {
  const res = await axios.get(`/api/projects/`);
  return res.data;
}

//Gets all employees
export async function getEmployeesByApi() {
  const res = await axios.get(`/api/users/`);
  return res.data;
}

//Gets project by projectId
export async function getProjectByApi(projectId: string) {
  return await axios.get(`/api/projects/` + projectId);
}

//Gets employee by employeeId
export async function getEmployeeTasksByApi(userId: string) {
  return await axios.get(`/api/tasks/` + userId);
}

// export async function getSampleTaskData() {
//   const sampleTaskData = await getTasksByApi();
//   return getTasksByApi();
//   // return (
//   //   [
//   //     {
//   //       id: 1,
//   //       name: "Design Chat",
//   //       description: "Appearance of Chat Bot",
//   //       status: "Completed",
//   //       projectId: 1,
//   //       employeeId: 1
//   //     },
//   //     {
//   //       id: 2,
//   //       name: "Gauge Interest",
//   //       description: "Will affect how the bot responds",
//   //       status: "Completed",
//   //       projectId: 1,
//   //       employeeId: 3
//   //     },
//   //     {
//   //       id: 3,
//   //       name: "Write Welcome Messages",
//   //       description: "Friendly and inviting",
//   //       status: "Review",
//   //       projectId: 1,
//   //       employeeId: 1
//   //     },
//   //     {
//   //       id: 4,
//   //       name: "Design Conversation Flow",
//   //       description: "Flow and dialogue for the chatbot",
//   //       status: "In-Progress",
//   //       projectId: 1,
//   //       employeeId: 2
//   //     },
//   //     {
//   //       id: 5,
//   //       name: "Publish User Guide",
//   //       description: "Guide to using the chatbot",
//   //       status: "Backlog",
//   //       projectId: 1,
//   //       employeeId: 3
//   //     },
//   //     {
//   //       id: 6,
//   //       name: "Create Accounts",
//   //       description: "Social Media Accounts",
//   //       status: "Completed",
//   //       projectId: 2,
//   //       employeeId: 2
//   //     },
//   //     {
//   //       id: 7,
//   //       name: "Schedule Posts",
//   //       description: "Release in orderly fashion",
//   //       status: "In Progress",
//   //       projectId: 2,
//   //       employeeId: 1
//   //     },
//   //     {
//   //       id: 8,
//   //       name: "Don't Get Cancelled",
//   //       description: "Stay away from Twitter.com",
//   //       status: "To-Do",
//   //       projectId: 2,
//   //       employeeId: 3,
//   //     },
//   //     {
//   //       id: 9,
//   //       name: "Engage with Followers",
//   //       description: "If we have any",
//   //       status: "Backlog",
//   //       projectId: 2,
//   //       employeeId: 3
//   //     }
//   //   ]
//   // )
// }

// export async function getSampleProjectData() {
//   const taskArray = await getSampleTaskData()
//   return (
//     [
//       {
//         id: 1,
//         name: 'Chat Bot',
//         description: 'Developing a chatbot for customer support',
//         deadline: '30/06/2023',
//         leaderId: 1,
//         tasks: taskArray
//       },
//       {
//         id: 2,
//         name: 'Social Media Management',
//         description: 'Develop Make-It-Alls brand using social media',
//         deadline: '15/09/2023',
//         leaderId: 2,
//         tasks: taskArray
//       }
//     ]
//   )
// }

// export function getSampleEmployeeData() {
//   return (
//     [
//       {
//         id: 1,
//         name: "Isaac"
//       },
//       {
//         id: 2,
//         name: "Wilfred Owen"
//       },
//       {
//         id: 3,
//         name: "Jordan Peele"
//       }
//     ]
//   )
// }
