import React, { useEffect, useState } from "react";
import { getProjectByApi, getEmployeesByApi } from "@/pages/analysis";
import { Title } from "@tremor/react";

type taskType = {
  id: number;
  name: string;
  description: string;
  projectId: number;
  employeeId: number;
};

type projectType = {
  id: number;
  name: string;
  description: string;
  deadline: string;
  leader: string;
  tasks: taskType[];
};

export function ProjectDetailsTile(props: projectType) {
  const formattedDeadline = new Date(props.deadline).toLocaleString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "numeric",
    minute: "numeric",
    hour12: false,
  });

  return (
    <div
      id="project-card"
      className="card flex flex-col shadow-md p-4 mx-4 w-1/2 bg-base-300 text-base-content"
    >
      <Title className="mb-2 text-2xl text-base-content">Project details</Title>

      <div className="flex flex-row justify-between">
        <span className="label-text text-base-content mb-0">Title</span>
        <span className="label-text text-base-content mb-0">Deadline</span>
      </div>
      <Title
        id="project-card-title"
        className="inline-flex flex-row justify-between mb-2"
      >
        {props.name}
        <span className="text-success">{formattedDeadline}</span>
      </Title>
      <hr />
      <br />
      <span className="label-text text-base-content mt-2 mb-0">
        Description
      </span>
      <p id="project-card-desc mt-0">{props.description}</p>
      <span className="label-text text-base-content mt-2 mb-0">Leader</span>
      <p id="project-card-leader mt-0">{props.leader}</p>
    </div>
  );
}

export default function ProjectDetailsProjectTile(props: any) {
  const [projectData, setProjectData] = useState<projectType | null>(null);
  const [projectLeader, setProjectLeader] = useState<string>("Team Led");

  useEffect(() => {
    if (props.currentProject !== 0) {
      fetchProjectData();
    }
  }, [props.currentProject]);

  const currentProject = props.currentProject;

  async function fetchProjectData() {
    const projectResponse = await getProjectByApi(currentProject);
    const allEmployeeData = await getEmployeesByApi();

    let leaderName;
    const leaderId = projectResponse.data.leaderId;

    if (Array.isArray(allEmployeeData.data)) {
      const leader = allEmployeeData.data.find(
        (employee: any) => employee.id === leaderId
      );
      if (leader) {
        leaderName = leader.name;
      }
    }

    setProjectData(projectResponse.data.data[0]);
    setProjectLeader(leaderName);
  }

  if (projectData == null || projectData == undefined) {
    return (
      <div className="card flex flex-col shadow-md p-4 mx-4 w-1/2 bg-base-300 text-base-content">
        <Title className="mb-2 text-2xl text-base-content">Project details</Title>
        <div className="flex h-full justify-center items-center mb-8">
          <Title className="text-2xl text-base-content">No project selected</Title>
        </div>
      </div>
    );
  }

  return (
    <ProjectDetailsTile
      id={projectData.id}
      name={projectData.name}
      description={projectData.description}
      deadline={projectData.deadline}
      leader={projectLeader}
      tasks={projectData.tasks}
    />
  );
}
